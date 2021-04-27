<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuyReadyProductNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buy_ready_product_notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('buy_ready_product_notificationable_id');
            $table->string('buy_ready_product_notificationable_type');
            $table->double('quantity')->default(0)->nullable();
            $table->string('status')->default(\App\BuyReadyProductNotification::CREATED)->nullable();
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
        Schema::dropIfExists('buy_ready_product_notifications');
    }
}
