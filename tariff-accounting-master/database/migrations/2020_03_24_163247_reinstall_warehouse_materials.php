<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReinstallWarehouseMaterials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('warehouse_materials','buy_id'))
        {
            Schema::table('warehouse_materials', function (Blueprint $table) {
                    $table->dropColumn('buy_id');
            });
        }
        if (Schema::hasColumn('warehouse_materials','qty_weight'))
        {
            Schema::table('warehouse_materials', function (Blueprint $table) {
                    $table->dropColumn('qty_weight');
            });
        }

        if (Schema::hasColumn('warehouse_materials','buy_material_id'))
        {
            Schema::table('warehouse_materials', function (Blueprint $table) {
                    $table->dropColumn('buy_material_id');
            });
        }

        if (!Schema::hasColumn('warehouse_materials','warehouse_materialable_type'))
        {
            Schema::table('warehouse_materials', function (Blueprint $table) {
                $table->string('warehouse_materialable_type')->nullable();
            });
        }

        if (!Schema::hasColumn('warehouse_materials','warehouse_materialable_id'))
        {
            Schema::table('warehouse_materials', function (Blueprint $table) {
                $table->string('warehouse_materialable_id')->nullable();
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
        if (!Schema::hasColumn('warehouse_materials','buy_id'))
        {
            Schema::table('warehouse_materials', function (Blueprint $table) {
                $table->bigInteger('buy_id')->nullable();
            });
        }

        if (!Schema::hasColumn('warehouse_materials','buy_material_id'))
        {
            Schema::table('warehouse_materials', function (Blueprint $table) {
                $table->bigInteger('buy_material_id')->nullable();
            });
        }

        if (!Schema::hasColumn('warehouse_materials','qty_weight'))
        {
            Schema::table('warehouse_materials', function (Blueprint $table) {
                $table->double('qty_weight',24,9)->nullable();
            });
        }

        if (Schema::hasColumn('warehouse_materials','warehouse_materialable_type'))
        {
            Schema::table('warehouse_materials', function (Blueprint $table) {
                $table->dropColumn('warehouse_materialable_type');
            });
        }

        if (Schema::hasColumn('warehouse_materials','warehouse_materialable_id'))
        {
            Schema::table('warehouse_materials', function (Blueprint $table) {
                $table->dropColumn('warehouse_materialable_id');
            });
        }
    }
}
