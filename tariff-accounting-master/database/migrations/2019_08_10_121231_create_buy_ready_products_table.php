<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuyReadyProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buy_ready_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('provider_id')->nullable();
            $table->boolean('paid')->default(0)->nullable();
            $table->integer('warehouse_id')->nullable();
            $table->date('date')->nullable();
            $table->double('total_price')->nullable();
            $table->string('comment', 500)->nullable();
            $table->bigInteger('number_contact')->nullable();
            $table->integer('status_id')->default(1)->nullable();
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
        Schema::dropIfExists('buy_ready_products');
    }
}
