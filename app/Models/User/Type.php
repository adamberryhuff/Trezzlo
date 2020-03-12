<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    /**
     * The table name attribute.
     *
     * @var string
     */
    public $table = 'user.type';

    /**
     * The fillable attributes
     *
     * @var array
     */
    public $fillable = ['id', 'type'];

    const TYPE_INTERNAL = 1;
    const TYPE_ADMIN    = 2;
    const TYPE_CUSTOMER = 3;
    const TYPE_REP      = 4;
    const TYPE_MANAGER  = 5;
    public static $types = [
        self::TYPE_INTERNAL => 'internal',
        self::TYPE_ADMIN    => 'admin',
        self::TYPE_CUSTOMER => 'customer',
        self::TYPE_REP      => 'rep',
        self::TYPE_MANAGER  => 'manager'
    ];
}

?>