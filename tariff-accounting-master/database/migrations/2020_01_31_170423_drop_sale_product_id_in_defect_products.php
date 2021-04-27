<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropSaleProductIdInDefectProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('defect_products', 'sale_product_id'))
        {
            Schema::table('defect_products', function (Blueprint $table)
            {
                $table->dropColumn('sale_product_id');
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
        Schema::table('defect_products', function (Blueprint $table) {
            //
        });
    }
}
