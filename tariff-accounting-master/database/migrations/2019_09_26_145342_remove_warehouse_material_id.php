<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveWarehouseMaterialId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_product_materials', function (Blueprint $table) {
            $table->dropColumn('warehouse_material_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_product_materials', function (Blueprint $table) {
            $table->bigInteger('warehouse_material_id')->nullable();
        });
    }
}
