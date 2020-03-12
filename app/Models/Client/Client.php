<?php

namespace App\Models\Client;

use App\Models\User\Type as UserType;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    /**
     * The table name attribute.
     *
     * @var string
     */
    public $table = 'client.client';

    /**
     * The fillable attributes
     *
     * @var array
     */
    public $fillable = ['name', 'cost', 'status_id'];

    /**
     * Get the client that owns the inbound message.
     */
    public function admin()
    {
        return $this->hasOne('App\Models\User\User')->where('user_type_id', '=', UserType::TYPE_ADMIN);
    }
}


?>