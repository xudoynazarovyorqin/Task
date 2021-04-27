<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleReadyProductItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_ready_product_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sale_id');
            $table->bigInteger('product_id');
            $table->double('quantity',24,9)->default(0);
            $table->double('shipped',24,9)->default(0);
            $table->double('selling_price',24,9)->default(0);
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
        Schema::dropIfExists('sale_ready_product_items');
    }
}
