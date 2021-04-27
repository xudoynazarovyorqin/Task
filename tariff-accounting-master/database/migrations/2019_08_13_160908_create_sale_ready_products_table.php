<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleReadyProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_ready_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('client_id')->nullable();
            $table->double('total_price')->nullable();
            $table->string('begin_date')->nullable();
            $table->string('end_date')->nullable();
            $table->double('payed_sum')->nullable();
            $table->double('debit')->nullable();
            $table->bigInteger('priority_id')->nullable();
            $table->bigInteger('state_id')->nullable();
            $table->bigInteger('warehouse_id')->nullable();
            $table->string('contract_number')->nullable();
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
        Schema::dropIfExists('sale_ready_products');
    }
}
