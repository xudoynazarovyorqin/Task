<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFromDateAndToDateToDistributionCosts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('distribution_costs', function (Blueprint $table) {
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->bigInteger('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('distribution_costs', function (Blueprint $table) {
            $table->dropColumn('from_date');
            $table->dropColumn('to_date');
            $table->dropColumn('user_id');
        });
    }
}
