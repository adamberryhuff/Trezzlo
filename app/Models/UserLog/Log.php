<?php

namespace App\Models\UserLog;

use Illuminate\Database\Eloquent\Model;
use App\Notifications\Slack;
use Notification;
use App\Models\User\User;
use App\Models\UserLog\Log as UserLog;

class Log extends Model
{
    /**
     * The table name attribute.
     *
     * @var string
     */
    public $table = 'user_log.log';
    
    /**
     * The fillable attributes
     *
     * @var array
     */
    public $fillable = ['event', 'event_id', 'user_id'];

    public static function log ($user_id, $event_id, $event_table_id)
    {
        $log                 = new UserLog;
        $log->user_id        = $user_id;
        $log->event_id       = $event_id;
        $log->event_table_id = $event_table_id;
        try {
            $log->save();
        } catch (\Exception $e) {
            $subject = 'Log model save failed!';
            $error   = __FILE__ . ' @ ' . __LINE__ . ': ' . $e->getMessage();
            $meta    = ['record' => $log];
            Notification::send(User::first(), new Slack($subject, $error, $meta));
        }
    }
}

?>