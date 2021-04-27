<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleReadyProductListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_ready_product_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sale_id');
            $table->bigInteger('product_id');
            $table->double('purchase_price')->nullable();
            $table->double('selling_price')->nullable();
            $table->double('quantity')->nullable();
            $table->double('total_price')->nullable();
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
        Schema::dropIfExists('sale_ready_product_lists');
    }
}
