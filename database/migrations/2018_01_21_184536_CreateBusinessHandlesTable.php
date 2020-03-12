<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessHandlesTable extends Migration
{
    const TABLE = 'client.handle';

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
            $table->integer('mechanism_id');
            $table->string('handle');
            $table->integer('status_id');
            $table->timestamps();
            $table->index('client_id');
            $table->index(['mechanism_id', 'handle']);
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
