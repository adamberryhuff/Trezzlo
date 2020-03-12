<?php

namespace App\Models\Message;

use Illuminate\Database\Eloquent\Model;

class Medium extends Model
{
    /**
     * The table name attribute.
     *
     * @var string
     */
    public $table = 'message.medium';
    
    /**
     * The fillable attributes
     *
     * @var array
     */
    public $fillable = ['id', 'medium'];

    // medium population and constants
    const SMS   = 1;
    const EMAIL = 2;
    public static $mediums = [
        self::SMS   => 'sms',
        self::EMAIL => 'email'
    ];
}

?>