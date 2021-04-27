<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\SaleReadyProductList;

class AddColumnsToSaleReadyProductLists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_ready_product_lists', function (Blueprint $table) {
            $table->string('when_added')->default(SaleReadyProductList::ON_CREATED);
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
            $table->dropColumn('when_added');
        });
    }
}
