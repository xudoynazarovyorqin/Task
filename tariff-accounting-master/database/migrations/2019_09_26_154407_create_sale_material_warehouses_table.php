<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleMaterialWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_material_warehouses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sale_id');
            $table->bigInteger('material_id');
            $table->bigInteger('warehouse_material_id');
            $table->double('quantity')->nullable()->default(0);
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
        Schema::dropIfExists('sale_material_warehouses');
    }
}
