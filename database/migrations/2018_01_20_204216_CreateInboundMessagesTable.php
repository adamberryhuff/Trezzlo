<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInboundMessagesTable extends Migration
{
    const TABLE = 'message.inbound';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->increments('id');
            $table->string('message_id');
            $table->integer('client_id')->nullable();
            $table->integer('handle_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('contact_id')->nullable();
            $table->integer('medium_id');
            $table->integer('mechanism_id');
            $table->string('to');
            $table->string('from');
            $table->string('body');
            $table->timestamps();
            $table->index('user_id');
            $table->index('client_id');
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
