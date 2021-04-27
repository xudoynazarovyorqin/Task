<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotEnoughProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('not_enough_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('buy_ready_product_notification_id');
            $table->bigInteger('product_id');
            $table->double('quantity',24,9)->default(0)->nullable();
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
        Schema::dropIfExists('not_enough_products');
    }
}
