<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShipmentProductableInShipmentProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipment_products', function (Blueprint $table) {
            $table->nullableMorphs('shipment_productable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipment_products', function (Blueprint $table) {
            $table->dropMorphs('shipment_productable');
        });
    }
}
