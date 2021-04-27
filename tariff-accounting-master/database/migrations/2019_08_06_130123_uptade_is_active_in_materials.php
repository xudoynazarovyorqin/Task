<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UptadeIsActiveInMaterials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('materials', 'is_active'))
        {
            Schema::table('materials', function (Blueprint $table)
            {
                $table->boolean('is_active')->default(1)->nullable()->change();
            });
        }else{
            Schema::table('materials', function (Blueprint $table)
            {
                $table->boolean('is_active')->default(1)->nullable();
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
        Schema::table('materials', function (Blueprint $table) {
            //
        });
    }
}
