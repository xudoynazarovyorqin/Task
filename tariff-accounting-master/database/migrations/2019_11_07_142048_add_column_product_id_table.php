<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnProductIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('defect_products', function (Blueprint $table) {
            $table->bigInteger('product_id')->nullable();
            $table->string('date')->nullable();
            $table->text('comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('defect_products', function (Blueprint $table) {
            $table->dropColumn('product_id');
            $table->dropColumn('date')->nullable();
            $table->dropColumn('comment')->nullable();
        });
    }
}
