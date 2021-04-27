<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationPartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_parts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('application_id')->nullable();
            $table->date('start_date')->nullable();
            $table->date('stop_date')->nullable();
            $table->double('amount', 24, 9)->default(0)->nullable();
            $table->double('paid', 24, 9)->default(0)->nullable();
            $table->string('status', 100)->default(\App\ApplicationPart::ACTIVE)->nullable();
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
        Schema::dropIfExists('application_parts');
    }
}
