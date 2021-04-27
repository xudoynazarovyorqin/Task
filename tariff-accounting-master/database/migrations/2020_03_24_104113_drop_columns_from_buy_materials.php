<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnsFromBuyMaterials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('buy_materials','name')){
            Schema::table('buy_materials', function (Blueprint $table) {
                $table->dropColumn('name');
            });
        }
        if (Schema::hasColumn('buy_materials','warehouse_type_id')){
            Schema::table('buy_materials', function (Blueprint $table) {
                $table->dropColumn('warehouse_type_id');
            });
        }
        if (Schema::hasColumn('buy_materials','total_price')){
            Schema::table('buy_materials', function (Blueprint $table) {
                $table->dropColumn('total_price');
            });
        }
        if (!Schema::hasColumn('buy_materials','selling_price')){
            Schema::table('buy_materials', function (Blueprint $table) {
                $table->double('selling_price',24,9)->default(0)->nullable();
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
        if (!Schema::hasColumn('buy_materials','name')){
            Schema::table('buy_materials', function (Blueprint $table) {
                $table->string('name')->nullable();
            });
        }
        if (!Schema::hasColumn('buy_materials','warehouse_type_id')){
            Schema::table('buy_materials', function (Blueprint $table) {
                $table->bigInteger('warehouse_type_id')->nullable();
            });
        }
        if (!Schema::hasColumn('buy_materials','total_price')){
            Schema::table('buy_materials', function (Blueprint $table) {
                $table->double('total_price')->nullable();
            });
        }
        if (Schema::hasColumn('buy_materials','selling_price')){
            Schema::table('buy_materials', function (Blueprint $table) {
                $table->dropColumn('selling_price');
            });
        }
    }
}
