<?php

namespace App\Models\Message;

use Illuminate\Database\Eloquent\Model;

class Unsubscribe extends Model
{
    /**
     * The table name attribute.
     *
     * @var string
     */
    public $table = 'message.unsubscribe';
    
    /**
     * The fillable attributes
     *
     * @var array
     */
    public $fillable = ['contact', 'medium_id', 'client_id'];
}

?>