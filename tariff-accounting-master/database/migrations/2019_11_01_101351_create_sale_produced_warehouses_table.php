<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleProducedWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_produced_warehouses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sale_id')->nullable();
            $table->bigInteger('sale_product_id')->nullable();
            $table->bigInteger('product_id')->nullable();
            $table->bigInteger('warehouse_id')->nullable();
            $table->double('quantity')->nullable();
            $table->double('remainder')->nullable();
            $table->bigInteger('price')->nullable();
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
        Schema::dropIfExists('sale_produced_warehouses');
    }
}
