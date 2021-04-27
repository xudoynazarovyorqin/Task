<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuyReadyProductListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buy_ready_product_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('buy_id');
            $table->bigInteger('product_id');
            $table->string('name', 100)->nullable();
            $table->double('qty_weight')->nullable();
            $table->bigInteger('price')->nullable();
            $table->double('total_price')->nullable();
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
        Schema::dropIfExists('buy_ready_product_lists');
    }
}
