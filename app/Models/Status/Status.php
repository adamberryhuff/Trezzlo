<?php

namespace App\Models\Status;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    /**
     * The table name attribute.
     *
     * @var string
     */
    public $table    = 'status.status';
    
    /**
     * The fillable attributes
     *
     * @var array
     */
    public $fillable = ['id', 'status'];
    
    // status population and constants
    const ACTIVE   = 1;
    const INACTIVE = 2;
    public static $statuses = [
        self::ACTIVE   => 'active',
        self::INACTIVE => 'inactive'
    ];
}


?>