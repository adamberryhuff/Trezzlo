<?php

namespace App\Models\Message;

use Illuminate\Database\Eloquent\Model;
use App\Models\Message\Medium;

class Mechanism extends Model
{
    /**
     * The table name attribute.
     *
     * @var string
     */
    public $table = 'message.mechanism';
    
    /**
     * The fillable attributes
     *
     * @var array
     */
    public $fillable = ['id', 'medium_id', 'mechanism'];

    // mechanism population and constants
    const TWILIO   = 1;
    const SES      = 2;
    public static $mechanisms = [
        self::TWILIO  => ['medium' => Medium::SMS, 'name' => 'twilio'],
        self::SES     => ['medium' => Medium::EMAIL, 'name' => 'ses']  
    ];
}

?>