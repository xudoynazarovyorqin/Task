<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameColumnSaleReadyProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_ready_products', function (Blueprint $table) {
            $table->renameColumn('payed_sum','paid_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_ready_products', function (Blueprint $table) {
            $table->renameColumn('paid_price','payed_sum');
        });
    }
}
