<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('number')->nullable();
            $table->dateTime('datetime')->nullable();
            $table->bigInteger('client_id')->nullable();
            $table->bigInteger('contract_client_id')->nullable();
            $table->bigInteger('status_id')->nullable();
            $table->string('console_number')->unique()->nullable();
            $table->string('object_name')->nullable();
            $table->string('object_address')->nullable();
            $table->string('object_street')->nullable();
            $table->string('object_home')->nullable();
            $table->string('object_corps')->nullable();
            $table->string('object_flat')->nullable();
            $table->double('amount', 24, 9)->default(0)->nullable();
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
        Schema::dropIfExists('applications');
    }
}
