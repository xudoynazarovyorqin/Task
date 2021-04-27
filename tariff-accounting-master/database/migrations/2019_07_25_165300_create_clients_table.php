<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id');            
            $table->string('name', 255)->nullable();
            $table->string('full_name', 255)->nullable();
            $table->string('sku', 100)->nullable();
            $table->string('phone', 100)->nullable();
            $table->string('fax', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('comment', 500)->nullable();
            $table->string('actual_address', 255)->nullable();
            $table->integer('type_id')->default(1)->nullable();

            /* yuridichiskiy rekvizit */
            $table->string('legal_address', 255)->nullable();
            $table->string('inn', 100)->nullable();
            $table->string('mfo', 100)->nullable();
            $table->string('okonx', 100)->nullable();
            $table->string('oked', 100)->nullable();
            $table->string('rkp_nds', 100)->nullable();
            /* yuridichiskiy rekvizit */
            
            $table->softDeletes();
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
        Schema::dropIfExists('clients');
    }
}
