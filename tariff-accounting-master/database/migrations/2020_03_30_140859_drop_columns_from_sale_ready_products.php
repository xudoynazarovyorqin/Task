<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnsFromSaleReadyProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('sale_ready_products','warehouse_id')){
            Schema::table('sale_ready_products', function (Blueprint $table) {
                $table->dropColumn('warehouse_id');
            });
        }
        if (Schema::hasColumn('sale_ready_products','end_date')){
            Schema::table('sale_ready_products', function (Blueprint $table) {
                $table->dropColumn('end_date');
            });
        }
        if (Schema::hasColumn('sale_ready_products','begin_date')){
            Schema::table('sale_ready_products', function (Blueprint $table) {
                $table->dropColumn('begin_date');
            });
        }
        if (Schema::hasColumn('sale_ready_products','priority_id')){
            Schema::table('sale_ready_products', function (Blueprint $table) {
                $table->dropColumn('priority_id');
            });
        }
        if (Schema::hasColumn('sale_ready_products','contract_number')){
            Schema::table('sale_ready_products', function (Blueprint $table) {
                $table->dropColumn('contract_number');
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
        if (!Schema::hasColumn('sale_ready_products','warehouse_id')){
            Schema::table('sale_ready_products', function (Blueprint $table) {
                $table->bigInteger('warehouse_id')->nullable();
            });
        }
        if (!Schema::hasColumn('sale_ready_products','priority_id')){
            Schema::table('sale_ready_products', function (Blueprint $table) {
                $table->bigInteger('priority_id')->nullable();
            });
        }
        if (!Schema::hasColumn('sale_ready_products','end_date')){
            Schema::table('sale_ready_products', function (Blueprint $table) {
                $table->string('end_date')->nullable();
            });
        }
        if (!Schema::hasColumn('sale_ready_products','begin_date')){
            Schema::table('sale_ready_products', function (Blueprint $table) {
                $table->string('begin_date')->nullable();
            });
        }
        if (!Schema::hasColumn('sale_ready_products','contract_number')){
            Schema::table('sale_ready_products', function (Blueprint $table) {
                $table->string('contract_number')->nullable();
            });
        }
    }
}
