<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeShipmentProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipment_products', function (Blueprint $table) {
            $table->dropColumn('price');
            $table->dropColumn('warehouse_id');
            $table->dropMorphs('shipment_productable');
            $table->decimal('issued_from_booked',24,9)->default(0.0);
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
            $table->nullableMorphs('shipment_productable');
            $table->double('price')->nullable();
            $table->bigInteger('warehouse_id')->nullable();
            $table->dropColumn('issued_from_booked');
        });
    }
}
