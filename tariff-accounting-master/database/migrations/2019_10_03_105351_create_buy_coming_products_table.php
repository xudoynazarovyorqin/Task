<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuyComingProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buy_coming_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('buy_id')->nullable();
            $table->bigInteger('product_id')->nullable();
            $table->bigInteger('warehouse_type_id')->nullable();
            $table->bigInteger('warehouse_product_id')->nullable();
            $table->double('qty_in_buy')->nullable();
            $table->double('qty_coming')->nullable();
            $table->bigInteger('buy_price')->nullable();
            $table->bigInteger('selling_price')->nullable();
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
        Schema::dropIfExists('buy_coming_products');
    }
}
