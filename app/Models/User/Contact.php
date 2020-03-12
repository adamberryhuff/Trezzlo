<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    /**
     * The table name attribute.
     *
     * @var string
     */
    public $table = 'user.contact';
    
    /**
     * The fillable attributes
     *
     * @var array
     */
    public $fillable = ['user_id', 'client_id', 'medium_id', 'contact', 'opted_in'];
}

?>