<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropNameAndWorkplaceIdInWarehouseMaterials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('warehouse_materials', 'name'))
        {
            Schema::table('warehouse_materials', function (Blueprint $table)
            {
                $table->dropColumn('name');
            });
        }

        if (Schema::hasColumn('warehouse_materials', 'workplace_id'))
        {
            Schema::table('warehouse_materials', function (Blueprint $table)
            {
                $table->dropColumn('workplace_id');
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
        Schema::table('warehouse_materials', function (Blueprint $table) {
            //
        });
    }
}
