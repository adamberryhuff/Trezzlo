<?php

namespace App\Models\UserLog;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /**
     * The table name attribute.
     *
     * @var string
     */
    public $table = 'user_log.event';
    
    /**
     * The fillable attributes
     *
     * @var array
     */
    public $fillable = ['event', 'description', 'id'];

    const EVENT_CUSTOMER_ADDED      = 1;
    const EVENT_RECEIVED            = 2;
    const EVENT_SENT                = 3;
    const EVENT_REVIEW_GENERATED    = 4;
    const EVENT_REVIEW_CLICKED      = 5;
    const EVENT_FEEDBACK_GENERATED  = 6;
    const EVENT_FEEDBACK_CLICKED    = 7;
    const EVENT_FEEDBACK_SUBMITTED  = 8;
    const EVENT_ERR_CONTENT         = 9;
    const EVENT_ERR_BUSINESS_STATUS = 10;
    const EVENT_ERR_HANDLE_STATUS   = 11;
    const EVENT_ERR_DO_NOT_CONTACT  = 12;
    const EVENT_LINK_GENERATED      = 13;
    const EVENT_LINK_CLICKED        = 14;
    public static $customer_events = [
        self::EVENT_CUSTOMER_ADDED => [
            'event'       => 'Customer Added', 
            'description' => 'New customer added to the system.'
        ],
        self::EVENT_RECEIVED => [
            'event'       => 'Received Message', 
            'description' => 'We received an incoming message'
        ],
        self::EVENT_SENT => [
            'event'       => 'Sent Message', 
            'description' => 'We sent an outgoing message'
        ],
        self::EVENT_REVIEW_GENERATED => [
            'event'       => 'Review Link Generated',
            'description' => 'System generated a review link'
        ],
        self::EVENT_REVIEW_CLICKED => [
            'event'       => 'Review Clicked', 
            'description' => 'User clicked the review link'
        ],
        self::EVENT_FEEDBACK_GENERATED => [
            'event'       => 'Feedback Link Generated',
            'description' => 'System generated a feedback link'
        ],
        self::EVENT_FEEDBACK_CLICKED => [
            'event'       => 'Feedback Clicked', 
            'description' => 'User clicked the feedback link'
        ],
        self::EVENT_FEEDBACK_SUBMITTED => [
            'event'       => 'Feedback Submitted', 
            'description' => 'User submitted the feedback link'
        ],
        self::EVENT_ERR_CONTENT => [
            'event'       => "Invalid Customer Response Error",
            'description' => "User replied in a way that doesn't trigger a system response"
        ],
        self::EVENT_ERR_BUSINESS_STATUS => [
            'event'       => "Invalid Accounts Status Error",
            'description' => "Lack of Payment"
        ],
        self::EVENT_ERR_HANDLE_STATUS => [
            'event'       => "Handle Status Error",
            "description" => "The handle status is not active."
        ],
        self::EVENT_ERR_DO_NOT_CONTACT => [
            "event"       => "'Do Not Contact' Error",
            "description" => "Customer Requested No Contact"
        ],
        self::EVENT_LINK_GENERATED => [
            "event"       => "Link Generated",
            "description" => "System generated a link"
        ],
        self::EVENT_LINK_CLICKED => [
            "event"       => "Link Clicked",
            "description" => "User clicked a link"
        ]
    ];
}

?>