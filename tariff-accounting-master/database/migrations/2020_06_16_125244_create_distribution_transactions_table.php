<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistributionTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distribution_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('distribution_cost_id')->nullable();
            $table->bigInteger('transaction_id')->nullable();
            $table->double('price', 24, 9)->default(0);
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
        Schema::dropIfExists('distribution_transactions');
    }
}
