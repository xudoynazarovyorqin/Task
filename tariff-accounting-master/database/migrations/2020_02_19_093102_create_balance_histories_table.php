<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBalanceHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balance_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('balance_historyable_id');
            $table->string('balance_historyable_type');
            $table->bigInteger('payment_type_id')->nullable();
            $table->double('amount')->default(0);
            $table->bigInteger('currency_id')->nullable();
            $table->double('rate')->default(1);
            $table->string('date')->nullable();
            $table->text('comment')->nullable();
            $table->bigInteger('user_id')->nullable();
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
        Schema::dropIfExists('balance_histories');
    }
}
