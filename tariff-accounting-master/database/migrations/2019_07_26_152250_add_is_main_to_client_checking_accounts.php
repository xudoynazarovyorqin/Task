<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsMainToClientCheckingAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_checking_accounts', function (Blueprint $table) {
            $table->tinyInteger('is_main')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_checking_accounts', function (Blueprint $table) {
            $table->dropColumn('is_main');
        });
    }
}
