<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropDebitFromSaleReadyProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('sale_ready_products', 'debit'))
        {
            Schema::table('sale_ready_products', function (Blueprint $table)
            {
                $table->dropColumn('debit');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_ready_products', function (Blueprint $table) {
            //
        });
    }
}
