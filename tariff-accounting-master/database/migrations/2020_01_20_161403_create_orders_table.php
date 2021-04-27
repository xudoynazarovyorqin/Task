<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('owner')->default(\App\Sale::FOR_CLIENT)->nullable();
            $table->bigInteger('client_id')->nullable();
            $table->bigInteger('contract_client_id')->nullable();
            $table->bigInteger('state_id')->nullable();
            $table->bigInteger('priority_id')->nullable();
            $table->string('begin_date')->nullable();
            $table->string('end_date')->nullable();
            $table->bigInteger('parent_id')->nullable();
            $table->boolean('is_child')->nullable();
            $table->double('amount',24,9)->default(0)->nullable();
            $table->double('paid',24,9)->default(0)->nullable();
            $table->string('production_type')->default(\App\Order::PRODUCTION)->nullable();
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
        Schema::dropIfExists('orders');
    }
}
