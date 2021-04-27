<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClickPaydocIdToPayuzTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payuz_transactions', function (Blueprint $table) {
            $table->string('click_paydoc_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payuz_transactions', function (Blueprint $table) {
            $table->dropColumn('click_paydoc_id');
        });
    }
}
