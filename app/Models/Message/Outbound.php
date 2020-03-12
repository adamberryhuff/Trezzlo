<?php

namespace App\Models\Message;

use Notification;
use App\Helpers;
use App\Models\User\User;
use App\Notifications\Slack;
use App\Models\Status\Status;
use App\Models\Message\Unsubscribe;
use App\Models\UserLog\Log as UserLog;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserLog\Event as UserLogEvent;
use App\Models\Link\Instance as LinkInstance;

class Outbound extends Model
{
    /**
     * The table name attribute.
     *
     * @var string
     */
    public $table = 'message.outbound';
    
    /**
     * The fillable attributes
     *
     * @var array
     */
    public $fillable = [
        'message_id', 'client_id', 'handle_id', 'user_id', 'contact_id', 
        'medium_id', 'mechanism_id', 'to', 'from', 'body'
    ];

    public static function boot()
    {
        parent::boot();


        self::created(function($model){
            // log event
            UserLog::log($model->user_id, UserLogEvent::EVENT_SENT, $model->id);
        });
    }

    /**
     * Get the client that owns the inbound message.
     */
    public function client()
    {
        return $this->belongsTo('App\Models\Client\Client');
    }

    /**
     * Get the handle status
     */
    public function handle()
    {
        return $this->belongsTo('App\Models\Client\Handle');
    }

    /**
     * trigger - triggers and outgoing message based off of an incoming messages
     *
     * @param object $inbound_model - the InboundMessages model
     *
     * @return @void
     */
    public function trigger ($inbound_model)
    {
        // set everything inbound model besides body and message_id
        $this->client_id     = $inbound_model->client_id;
        $this->handle_id     = $inbound_model->handle_id;
        $this->user_id       = $inbound_model->user_id;
        $this->contact_id    = $inbound_model->contact_id;
        $this->medium_id     = $inbound_model->medium_id;
        $this->mechanism_id  = $inbound_model->mechanism_id;
        $this->to            = $inbound_model->from;
        $this->from          = $inbound_model->to;

        // check that we can send the message
        if (!$this->canSend($inbound_model->id, $inbound_model->body)) {
            return;
        }

        // compile message content
        $this->body = $this->compileMessageBody($inbound_model->body);
        if (empty($this->body)) {
            return;
        }

        $this->message_id = Helpers::sms($this->handle->handle, $this->to, $this->body);
        try {
            $this->save();
        } catch (\Exception $e) {
            $subject = 'Outbound model save failed!';
            $error   = __FILE__ . ' @ ' . __LINE__ . ': ' . $e->getMessage();
            $meta    = ['record' => $this];
            Notification::send(User::first(), new Slack($subject, $error, $meta));
        }
    }

    /**
     * canSend - Determines if we can send an outgoing message
     *
     * @param integer $inbound_id   - the id of the incoming message
     * @param string  $inbound_body - the inbound body that triggered the outgoing message
     *
     * @return boolean
     */
    protected function canSend ($inbound_id, $inbound_body)
    {
        $send = true;

        // validate response
        $body = trim(strtolower($inbound_body));
        if (!in_array($body, ['discount', 'yes', 'no'])) {
            UserLog::log($this->user_id, UserLogEvent::EVENT_ERR_CONTENT, $inbound_id);
            $send = false;
        }

        // validate that the response is associated with a client
        if (!$this->client) {
            $subject = 'Message Not Associated with Client!';
            $error   = __FILE__ . ' @ ' . __LINE__;
            $meta    = ['record' => $this];
            Notification::send(User::first(), new Slack($subject, $error, $meta));
            return false;
        }

        // validate client status
        if ($this->client->status_id != Status::ACTIVE) {
            UserLog::log($this->user_id, UserLogEvent::EVENT_ERR_BUSINESS_STATUS, $inbound_id);
            $send = false;
        }

        // check handle status
        if ($this->handle->status_id != Status::ACTIVE) {
            $subject = 'Active Client with Inactive Handle!';
            $error   = __FILE__ . ' @ ' . __LINE__;
            $meta    = ['record' => $this];
            Notification::send(User::first(), new Slack($subject, $error, $meta, Slack::WARNING));
            UserLog::log($this->user_id, UserLogEvent::EVENT_ERR_HANDLE_STATUS, $inbound_id);
            $send = false;
        }

        // check for never email
        $dnc = Unsubscribe::where('contact', $this->to)
            ->where('medium_id', $this->medium_id)
            ->where(function ($query) {
                $query->where('client_id', $this->client_id)
                      ->orWhere('client_id', null);
            })
            ->first();
        if (!empty($dnc)) {
            UserLog::log($this->user_id, UserLogEvent::EVENT_ERR_DO_NOT_CONTACT, $inbound_id);
            $send = false;
        }

        return $send;
    }

    /**
     * compileMessageBody - Compiles the message body based off the incoming message
     *
     * @param string $inbound_body - the message body that triggered the outgoing message
     *
     * @return string
     */
    protected function compileMessageBody ($inbound_body)
    {
        // TODO: These magic numbers will be set by the builder later once I add
        //       the conversation complexity. For now we hardcode them. 2 should actually
        //       be a query.
        $body = strtolower(trim($inbound_body));
        if ($body == 'discount') {
            $body  = "Did you have a good experience at " . $this->client->name . "?";
            $body .= " Please response 'Yes', 'No', or 'Stop'.";
        } else if ($body == 'no') {
            $link = $this->getLink(1);
            if (!$link) {
                return false;
            }
            $body  = "We're sorry you had a negative experience. ";
            $body .= "Please click the following link to leave us feedback: " . $link;
        } else if ($body == 'yes') {
            $link = $this->getLink(2);
            if (!$link) {
                return false;
            }
            $body  = "Thank you. Please click the following link if you would like to leave us a review: ";
            $body .= $link;
        } else {
            return false;
        }

        return $body;
    }

    protected function getLink ($link_id)
    {
        // identifier
        $id = $this->getRandomString();

        // base url
        $base = env('SERVER_HOST');
        if (env('APP_DEBUG')) {
            $base = env('LOCAL_HOST');
        }
        $url = $base . 'l/' . $id;

        // save URL
        try {
            $link              = new LinkInstance();
            $link->link_id     = $link_id;
            $link->client_id   = $this->client_id;
            $link->user_id     = $this->user_id;
            $link->url_id      = $id;
            $link->save();
        } catch (\Exception $e) {
            $subject = 'Instance model save failed!';
            $error   = __FILE__ . ' @ ' . __LINE__ . ': ' . $e->getMessage();
            $meta    = ['record' => $link];
            Notification::send(User::first(), new Slack($subject, $error, $meta));
            return false;
        }
        return $url;
    }

    /**
     * getRandomString - generates a random string used in the links
     *
     * @return string
     */
    protected function getRandomString() {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-';
        $string = '';

        for ($i = 0; $i < 12; $i++) {
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        return $string;
    }
}

?>