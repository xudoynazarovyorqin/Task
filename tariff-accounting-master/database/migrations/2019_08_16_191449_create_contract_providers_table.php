<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_providers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('number')->nullable();
            $table->string('begin_date')->nullable();
            $table->bigInteger('provider_id')->nullable();
            $table->integer('status_id')->nullable();
            $table->double('sum')->nullable();
            $table->string('comment', 255)->nullable();
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
        Schema::dropIfExists('contract_providers');
    }
}
