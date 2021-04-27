<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToSaleReadyProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_ready_products', function (Blueprint $table) {
            $table->bigInteger('contract_client_id');
            $table->bigInteger('user_id');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_ready_products', function (Blueprint $table) {
            $table->dropColumn('contract_client_id');
            $table->dropColumn('user_id');
            $table->dropColumn('deleted_at');
        });
    }
}
