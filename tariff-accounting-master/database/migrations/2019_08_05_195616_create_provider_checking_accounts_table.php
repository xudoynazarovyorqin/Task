<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProviderCheckingAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provider_checking_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('provider_id');
            $table->string('bank', 100)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('correspondent_account', 100)->nullable();
            $table->string('checking_account', 100)->nullable();            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('provider_checking_accounts');
    }
}
