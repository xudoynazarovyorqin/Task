<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnFromWarehouseMatrials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse_materials', function (Blueprint $table) {
            $table->decimal('price',24,9)->change();
            $table->decimal('total_coming',24,9)->change();
            $table->decimal('remainder',24,9)->change();
            $table->decimal('qty_weight',24,9)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warehouse_materials', function (Blueprint $table) {
            //
        });
    }
}
