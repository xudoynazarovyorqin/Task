<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScoresTable extends Migration
{
    public function up()
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->nullableMorphs('scoreable');
            $table->string('name')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('mfo')->nullable();
            $table->string('number')->nullable();
            $table->bigInteger('currency_id')->nullable();
            $table->boolean('active')->default(false);
            $table->double('incoming',24)->default(0.0);
            $table->double('outgoing',24)->default(0.0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('scores');
    }
}
