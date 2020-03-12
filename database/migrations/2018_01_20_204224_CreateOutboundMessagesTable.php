<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOutboundMessagesTable extends Migration
{
    const TABLE = 'message.outbound';

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
            $table->integer('client_id');
            $table->integer('handle_id');
            $table->integer('user_id');
            $table->integer('contact_id');
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
