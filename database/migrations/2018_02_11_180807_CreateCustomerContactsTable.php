<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerContactsTable extends Migration
{
    const TABLE = 'user.contact';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id');
            $table->integer('user_id');
            $table->integer('medium_id');
            $table->string('contact');
            $table->integer('opted_in')->default(0);
            $table->timestamps();
            $table->index(['client_id', 'contact', 'medium_id']);
            $table->index(['user_id', 'medium_id']);
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
