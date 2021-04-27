<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropEmployeesAndSaleProducedWarehousesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('employees')) {
            Schema::drop('employees');
        }

        if (Schema::hasTable('employee_group_employees')) {
            Schema::drop('employee_group_employees');
        }

        if (Schema::hasTable('sale_employees')) {
            Schema::drop('sale_employees');
        }

        if (Schema::hasTable('sale_produced_warehouses')) {
            Schema::drop('sale_produced_warehouses');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
