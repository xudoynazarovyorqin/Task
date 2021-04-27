<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnNameDefectProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('defect_products', function (Blueprint $table) {
            $table->renameColumn('product_id','sale_product_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
            if (Schema::hasColumn('defect_products','sale_product_id')){
                Schema::table('defect_products', function (Blueprint $table) {
                    $table->renameColumn('sale_product_id','product_id');
                });
            }
    }
}
