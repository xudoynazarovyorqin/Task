<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeRenameAttrInWarehouseProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('warehouse_products', 'name'))
        {
            Schema::table('warehouse_products', function (Blueprint $table)
            {
                $table->dropColumn('name');
            });
        }

        if (Schema::hasColumn('warehouse_products', 'total_coming'))
        {
            Schema::table('warehouse_products', function (Blueprint $table)
            {
                $table->renameColumn('total_coming', 'receive');
            });
        }

        Schema::table('warehouse_products', function (Blueprint $table) {
            $table->string('owner')->nullable()->default(\App\WarehouseProduct::PROVIDER);
            $table->nullableMorphs('agentable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warehouse_products', function (Blueprint $table) {
            $table->dropColumn('owner');
            $table->dropColumn('agentable_id');
            $table->dropColumn('agentable_type');
        });
    }
}
