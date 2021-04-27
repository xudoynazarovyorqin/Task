<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropIsFullColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('buy_materials','is_full')){
            Schema::table('buy_materials', function (Blueprint $table) {
                $table->dropColumn('is_full');
            });
        }
        if (Schema::hasColumn('buy_ready_product_lists','is_full')){
            Schema::table('buy_ready_product_lists', function (Blueprint $table) {
                $table->dropColumn('is_full');
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
        if (!Schema::hasColumn('buy_materials','is_full')){
            Schema::table('buy_materials', function (Blueprint $table) {
                $table->tinyInteger('is_full')->default(0);
            });
        }
        if (!Schema::hasColumn('buy_ready_product_lists','is_full')){
            Schema::table('buy_ready_product_lists', function (Blueprint $table) {
                $table->tinyInteger('is_full')->default(0);
            });
        }
    }
}
