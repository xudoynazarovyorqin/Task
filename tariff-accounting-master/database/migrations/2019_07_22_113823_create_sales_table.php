<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('client_id')->nullable();
            $table->double('total_amount')->nullable();
            $table->string('begin_date')->nullable();
            $table->string('end_date')->nullable();
            $table->double('payed_sum')->nullable();
            $table->double('debit')->nullable();
            $table->string('priority')->nullable();
            $table->bigInteger('state_id')->nullable();
            $table->bigInteger('warehouse_id')->nullable();
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
        Schema::dropIfExists('sales');
    }
}
