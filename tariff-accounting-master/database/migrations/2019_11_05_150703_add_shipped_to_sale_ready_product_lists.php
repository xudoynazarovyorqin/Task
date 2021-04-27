<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShippedToSaleReadyProductLists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_ready_product_lists', function (Blueprint $table) {
            $table->double('shipped')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_ready_product_lists', function (Blueprint $table) {
            $table->dropColumn('shipped');
        });
    }
}
