<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerLinksTable extends Migration
{
    const TABLE = 'link.instance';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('link_id');
            $table->integer('client_id');
            $table->integer('user_id');
            $table->integer('visits')->default(0);
            $table->string('url_id');
            $table->timestamps();
            $table->index('url_id');
            $table->index(['client_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(self::TABLE);
    }
}
