<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('number')->nullable();
            $table->string('begin_date')->nullable();
            $table->bigInteger('client_id')->nullable();
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
        Schema::dropIfExists('contract_clients');
    }
}
