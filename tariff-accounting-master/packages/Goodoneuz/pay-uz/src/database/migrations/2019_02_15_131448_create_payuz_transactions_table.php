<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayuzTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('pay-uz.db_prefix') . 'transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('payment_system');
            $table->string('system_transaction_id');
            $table->double('amount',15,5);
            $table->integer('currency_code');
            $table->integer('state');
            $table->string('updated_time')->nullable();
            $table->string('comment')->nullable();
            $table->string('detail')->nullable();
            $table->string('transactionable_type')->nullable();
            $table->integer('transactionable_id')->nullable();
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
        Schema::dropIfExists(config('pay-uz.db_prefix') . 'transactions');
    }
}
