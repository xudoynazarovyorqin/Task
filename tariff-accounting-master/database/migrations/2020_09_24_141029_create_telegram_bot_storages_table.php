<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelegramBotStoragesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telegram_bot_storages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('chat_id')->nullable();
            $table->string('console_number')->nullable();
            $table->string('last_input')->nullable();
            $table->string('last_output')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('telegram_bot_storages');
    }
}
