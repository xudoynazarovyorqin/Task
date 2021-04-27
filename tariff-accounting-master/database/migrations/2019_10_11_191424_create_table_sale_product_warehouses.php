<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSaleProductWarehouses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_product_warehouses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sale_id');
            $table->bigInteger('product_id');
            $table->bigInteger('warehouse_product_id');
            $table->double('quantity')->nullable()->default(0);
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
        Schema::dropIfExists('sale_product_warehouses');
    }
}
