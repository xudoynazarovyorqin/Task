<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeBeginDateStringToDateInContractClients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('contract_clients', 'begin_date'))
        {
            Schema::table('contract_clients', function (Blueprint $table)
            {
                $table->dropColumn('begin_date');
            });
        }
        Schema::table('contract_clients', function (Blueprint $table)
        {
            $table->date('begin_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('contract_clients', 'begin_date'))
        {
            Schema::table('contract_clients', function (Blueprint $table)
            {
                $table->dropColumn('begin_date');
            });
        }
        Schema::table('contract_clients', function (Blueprint $table)
        {
            $table->string('begin_date')->nullable();
        });
    }
}
