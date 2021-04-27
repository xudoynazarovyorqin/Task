<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssembliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assemblies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('owner')->nullable()->default(\App\Sale::FOR_FIRM);
            $table->bigInteger('state_id')->nullable();
            $table->bigInteger('priority_id')->nullable();
            $table->bigInteger('assemblyable_id')->nullable();
            $table->string('assemblyable_type')->nullable();
            $table->string('begin_date')->nullable();
            $table->string('end_date')->nullable();
            $table->bigInteger('parent_id')->nullable();
            $table->boolean('is_child')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assemblies');
    }
}
