<?php

namespace App\Models\Link;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    /**
     * The table name attribute.
     *
     * @var string
     */
    public $table = 'link.link';

    /**
     * The fillable attributes
     *
     * @var array
     */
    public $fillable = ['client_id', 'name', 'redirect'];

    /**
     * The table constant attribute.
     *
     * @var string
     */
    const LINK_FEEDBACK = 1;
}

?>