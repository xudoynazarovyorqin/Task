<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('payment_type_id')->nullable();
            $table->double('price')->nullable();
            $table->bigInteger('currency_id')->nullable();
            $table->double('rate')->nullable();
            $table->string('date')->nullable();
            $table->string('comment', 255)->nullable();
            $table->nullableMorphs('transactionable');
            $table->bigInteger('user_id')->nullable();
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
        Schema::dropIfExists('transactions');
    }
}
