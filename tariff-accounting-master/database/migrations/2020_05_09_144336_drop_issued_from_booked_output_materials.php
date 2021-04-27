<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropIssuedFromBookedOutputMaterials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('output_materials', function (Blueprint $table) {
            $table->dropColumn('issued_from_reservation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('output_materials', function (Blueprint $table) {
            $table->decimal('issued_from_reservation',24,9)->default(0.0);
        });
    }
}
