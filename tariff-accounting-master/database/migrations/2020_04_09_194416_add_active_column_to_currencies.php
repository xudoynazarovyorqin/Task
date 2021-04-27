<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActiveColumnToCurrencies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('currencies', function (Blueprint $table) {
            $table->boolean('active')->default(false);
        });
        if (Schema::hasColumn('currencies','rate')){
            Schema::table('currencies', function (Blueprint $table) {
                $table->dropColumn('rate');
            });
            Schema::table('currencies', function (Blueprint $table) {
                $table->double('rate',24,9)->default(1);
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
        Schema::table('currencies', function (Blueprint $table) {
            $table->dropColumn('active');
        });
    }
}
