<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConclusionDateAndTerminationDateToContractClients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contract_clients', function (Blueprint $table) {
            $table->date('conclusion_date')->nullable();
            $table->date('termination_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contract_clients', function (Blueprint $table) {
            $table->dropColumn('conclusion_date');
            $table->dropColumn('termination_date');
        });
    }
}
