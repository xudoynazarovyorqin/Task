<?php

use App\SaleProduct;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAddedOnUpdatedColumnToSaleProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_products', function (Blueprint $table) {
            $table->string('when_added')->default(SaleProduct::ON_CREATED);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_products', function (Blueprint $table) {
            $table->dropColumn('when_added');
        });
    }
}
