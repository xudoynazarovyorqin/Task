<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTotalPriceToBuyMaterials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buy_materials', function (Blueprint $table) {
            $table->double('total_price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('buy_materials', 'total_amount'))
        {
            Schema::table('buy_materials', function (Blueprint $table)
            {
                $table->dropColumn('total_amount');
            });
        }
    }
}
