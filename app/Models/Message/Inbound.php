<?php

namespace App\Models\Message;

use Illuminate\Database\Eloquent\Model;
use App\Notifications\SesSendError;
use App\Models\UserLog\Log as UserLog;
use App\Models\UserLog\Event as UserLogEvent;
use App\Models\Message\Outbound as OutboundMessage;
use Notification;

class Inbound extends Model
{
    /**
     * The table name attribute.
     *
     * @var string
     */
    public $table = 'message.inbound';
    
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
            if (!empty($model->user_id)) {
                // log event
                UserLog::log($model->user_id, UserLogEvent::EVENT_RECEIVED, $model->id);
            }

            // trigger outbound
            $outbound = new OutboundMessage;
            $outbound->trigger($model);
        });
    }
}   

?>