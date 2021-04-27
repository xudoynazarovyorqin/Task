<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWaitingToBuyAssemblyMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assembly_materials', function (Blueprint $table) {
            $table->double('waiting_to_buy',24,9)->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assembly_materials', function (Blueprint $table) {
            $table->dropColumn('waiting_to_buy');
        });
    }
}
