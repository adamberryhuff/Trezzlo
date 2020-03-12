<?php

namespace App\Models\User;

use App\Models\Message\Medium;
use App\Models\UserLog\Log as UserLog;
use Illuminate\Notifications\Notifiable;
use App\Models\UserLog\Event as UserLogEvent;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    
    /**
     * The table name attribute.
     *
     * @var string
     */
    public $table = 'user.user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_type_id', 'client_id', 'first_name',
        'last_name', 'username', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * boot - runs every time a record is touched
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        // log customer creation
        self::created(function($model){
            UserLog::log($model->id, UserLogEvent::EVENT_CUSTOMER_ADDED, $model->id);
        });
    }
    
    /**
     * Route notifications for the Slack channel.
     *
     * @return string
     */
    public function routeNotificationForSlack()
    {
        return env('SLACK_WEBHOOK_URL');
    }


    /**
     * Get the comments for the blog post.
     */
    public function contacts()
    {
        return $this->hasMany('App\Models\User\Contact', 'user_id');
    }

    /**
     * Get the comments for the blog post.
     */
    public function email()
    {
        return $this->hasOne('App\Models\User\Contact', 'user_id')->where('medium_id', '=', Medium::EMAIL);
    }

    /**
     * Get the comments for the blog post.
     */
    public function phone()
    {
        return $this->hasOne('App\Models\User\Contact', 'user_id')->where('medium_id', '=', Medium::SMS);
    }

    /**
     * Get the comments for the blog post.
     */
    public function feedback()
    {
        return $this->hasOne('App\Models\User\Feedback', 'user_id');
    }
}


?>