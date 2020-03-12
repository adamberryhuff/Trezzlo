<?php

namespace App\Models\Link;

use Illuminate\Database\Eloquent\Model;
use App\Mail\LinkClicked;
use App\Helpers;
use App\Models\Link\Link;
use App\Models\UserLog\Log as UserLog;
use App\Models\UserLog\Event as UserLogEvent;

class Instance extends Model
{
    /**
     * The table name attribute.
     *
     * @var string
     */
    public $table = 'link.instance';

    /**
     * The fillable attributes
     *
     * @var array
     */
    public $fillable = ['link_id', 'client_id', 'user_id', 'visits', 'url_id'];

    public static function boot()
    {
        parent::boot();

        // log link creation
        self::created(function($model){
            $event = $model->link_id == Link::LINK_FEEDBACK 
                ? UserLogEvent::EVENT_FEEDBACK_GENERATED
                : UserLogEvent::EVENT_LINK_GENERATED;
            UserLog::log($model->user_id, $event, $model->id);
        });
        self::updated(function($model) {
            // log link clicks
            if ($model->visits > $model['original']['visits'] && empty($_GET['submit'])) {
                $event = $model->link_id == Link::LINK_FEEDBACK 
                    ? UserLogEvent::EVENT_FEEDBACK_CLICKED
                    : UserLogEvent::EVENT_LINK_CLICKED;
                UserLog::log($model->user_id, $event, $model->id);
            }

            // should become generic and opt-in
            if ($model->visits == 1 && $model['original']['visits'] == 0 && $model->link_id != Link::LINK_FEEDBACK) {
                if (!empty($model->client->admin->email->contact)) {
                    Helpers::mail($model->client->admin->email->contact, new LinkClicked($model));
                }
            }
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
     * Get the user that owns the inbound message.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User\User');
    }

    /**
     * Get the link that owns the inbound message.
     */
    public function link()
    {
        return $this->belongsTo('App\Models\Link\Link');
    }
}

?>