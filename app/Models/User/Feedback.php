<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use App\Mail\FeedbackSubmitted;
use App\Models\UserLog\Log as UserLog;
use App\Models\UserLog\Event as UserLogEvent;
use App\Helpers;

class Feedback extends Model
{
    /**
     * The table name attribute.
     *
     * @var string
     */
    public $table = 'user.feedback';

    /**
     * The fillable attributes
     *
     * @var array
     */
    public $fillable = ['client_id', 'user_id', 'feedback'];

    public static function boot()
    {
        parent::boot();
        self::created(function($model) {
            // log and email on feedback submission
            UserLog::log($model->user_id, UserLogEvent::EVENT_FEEDBACK_SUBMITTED, $model->id);
            if (!empty($model->client->admin->email->contact)) {
                Helpers::mail($model->client->admin->email->contact, new FeedbackSubmitted($model));
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
}


?>