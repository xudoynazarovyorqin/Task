<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnFromSales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('client_id');
            $table->dropColumn('total_amount');
            $table->dropColumn('payed_sum');
            $table->dropColumn('debit');
            $table->dropColumn('warehouse_id');
            $table->dropColumn('contract_number');
            $table->dropColumn('contract_client_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->bigInteger('client_id')->nullable();
            $table->double('total_amount')->nullable();
            $table->double('payed_sum')->nullable();
            $table->double('debit')->nullable();
            $table->bigInteger('warehouse_id')->nullable();
            $table->string('contract_number')->nullable();
            $table->bigInteger('contract_client_id	')->nullable();
        });
    }
}
