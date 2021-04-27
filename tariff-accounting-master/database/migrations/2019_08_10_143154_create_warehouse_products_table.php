<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('buy_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->string('name', 100)->nullable();
            $table->double('qty_weight')->nullable();
            $table->double('remainder')->nullable();
            $table->bigInteger('price')->nullable();
            $table->bigInteger('warehouse_type_id')->nullable();
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
        Schema::dropIfExists('warehouse_products');
    }
}
