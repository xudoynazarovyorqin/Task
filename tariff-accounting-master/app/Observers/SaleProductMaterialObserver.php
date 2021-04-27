<?php

namespace App\Observers;

use App\SaleMaterial;
use App\SaleProductMaterial;

class SaleProductMaterialObserver
{
    public function created(SaleProductMaterial $saleProductMaterial)
    {
        if ($material = $saleProductMaterial->material){
            if ($material->measurement_changeable == true){
                $saleProductMaterial->measurement_rate = $material->additional_measurement_rate;
                $saleProductMaterial->update();
            }
        }

        if($saleMaterial = SaleMaterial::where('material_id',$saleProductMaterial->material_id)->where('sale_id',$saleProductMaterial->sale_product->sale_id)->first()){
            $saleMaterial->update([
                'total' => $saleMaterial->total + $saleProductMaterial->quantity
            ]);
        }else{
            SaleMaterial::create([
                'sale_id'       => $saleProductMaterial->sale_product->sale_id,
                'material_id'   => $saleProductMaterial->material_id,
                'total'         => $saleProductMaterial->quantity,
                'ready'         => 0,
                'waiting_to_buy'=> 0,
            ]);
        }
    }

    public function updated(SaleProductMaterial $saleProductMaterial)
    {
        //
    }

    public function deleted(SaleProductMaterial $saleProductMaterial)
    {
    }

    public function restored(SaleProductMaterial $saleProductMaterial)
    {
        //
    }

    public function forceDeleted(SaleProductMaterial $saleProductMaterial)
    {
        //
    }
}
