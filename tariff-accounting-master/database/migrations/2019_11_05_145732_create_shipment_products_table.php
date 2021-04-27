<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipmentProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipment_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('shipment_id')->nullable();
            $table->bigInteger('sale_product_id')->nullable();
            $table->bigInteger('product_id')->nullable();
            $table->double('quantity')->nullable();
            $table->double('price')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('shipment_products');
    }
}
