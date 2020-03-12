<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

class Handle extends Model
{
    /**
     * The table name attribute.
     *
     * @var string
     */
    public $table = 'client.handle';

    /**
     * The fillable attributes
     *
     * @var array
     */
    public $fillable = ['client_id', 'mechanism_id', 'handle', 'status_id'];
}

?>