<?php

namespace App\Observers;

use App\SaleMaterial;
use App\SaleMaterialWarehouse;
use App\WarehouseMaterial;
use Illuminate\Support\Facades\Log;

class SaleMaterialWarehouseObserver
{
    /**
     * Handle the sale material warehouse "created" event.
     *
     * @param  \App\SaleMaterialWarehouse  $saleMaterialWarehouse
     * @return void
     */
    public function created(SaleMaterialWarehouse $saleMaterialWarehouse)
    {
        if ($warehouse_material = $saleMaterialWarehouse->warehouse_material){
            $warehouse_material->remainder -= $saleMaterialWarehouse->quantity;
            $warehouse_material->update();
        };

        if($sale_material = SaleMaterial::where('sale_id',$saleMaterialWarehouse->sale_id)->where('material_id',$saleMaterialWarehouse->material_id)->first()){
            $sale_material->ready = $sale_material->ready + $saleMaterialWarehouse->quantity;
            $sale_material->update();
        }
    }


    /**
     * Handle the sale material warehouse "updated" event.
     *
     * @param  \App\SaleMaterialWarehouse  $saleMaterialWarehouse
     * @return void
     */
    public function updating(SaleMaterialWarehouse $saleMaterialWarehouse)
    {
        $original = $saleMaterialWarehouse->getOriginal();
        if($warehouse_material = WarehouseMaterial::find($saleMaterialWarehouse->warehouse_material_id)){
            $warehouse_material->remainder -=$original['back'];
            $warehouse_material->remainder +=$saleMaterialWarehouse->back;
            $warehouse_material->update();
        };
    }

    /**
     * Handle the sale material warehouse "updated" event.
     *
     * @param  \App\SaleMaterialWarehouse  $saleMaterialWarehouse
     * @return void
     */
    public function updated(SaleMaterialWarehouse $saleMaterialWarehouse)
    {
        //
    }

    /**
     * Handle the sale material warehouse "deleted" event.
     *
     * @param  \App\SaleMaterialWarehouse  $saleMaterialWarehouse
     * @return void
     */
    public function deleted(SaleMaterialWarehouse $saleMaterialWarehouse)
    {
        if ($warehouse_material = $saleMaterialWarehouse->warehouse_material){
            $warehouse_material->remainder += $saleMaterialWarehouse->quantity;
            $warehouse_material->update();
        };

        if($sale_material = SaleMaterial::where('sale_id',$saleMaterialWarehouse->sale_id)->where('material_id',$saleMaterialWarehouse->material_id)->first()){
            $sale_material->ready = $sale_material->ready - $saleMaterialWarehouse->quantity;
            $sale_material->update();
        }
    }

    /**
     * Handle the sale material warehouse "restored" event.
     *
     * @param  \App\SaleMaterialWarehouse  $saleMaterialWarehouse
     * @return void
     */
    public function restored(SaleMaterialWarehouse $saleMaterialWarehouse)
    {
        //
    }

    /**
     * Handle the sale material warehouse "force deleted" event.
     *
     * @param  \App\SaleMaterialWarehouse  $saleMaterialWarehouse
     * @return void
     */
    public function forceDeleted(SaleMaterialWarehouse $saleMaterialWarehouse)
    {
        //
    }
}
