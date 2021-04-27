<?php

namespace App\Observers;

use App\SaleAdditionalMaterial;
use App\SaleMaterial;
use App\SaleProductMaterial;
use Illuminate\Support\Facades\Log;

class SaleAdditionalMaterialObserver
{

    public function created(SaleAdditionalMaterial $saleAdditionalMaterial)
    {
        if ($material = $saleAdditionalMaterial->material){
            if ($material->measurement_changeable == true){
                $rate = $material->additional_measurement_rate > 0 ? $material->additional_measurement_rate : 1;
                $saleAdditionalMaterial->quantity = $saleAdditionalMaterial->quantity / $rate;
                $saleAdditionalMaterial->measurement_rate = $rate;
                $saleAdditionalMaterial->update();
            }
        }

        if($saleMaterial = SaleMaterial::where('material_id',$saleAdditionalMaterial->material_id)->where('sale_id',$saleAdditionalMaterial->sale_id)->first()){
            $saleMaterial->update([
                'total' => $saleMaterial->total + $saleAdditionalMaterial->quantity
            ]);
        }else{
            SaleMaterial::create([
                'sale_id' => $saleAdditionalMaterial->sale_id,
                'material_id' => $saleAdditionalMaterial->material_id,
                'total'   => $saleAdditionalMaterial->quantity,
            ]);
        }

    }

    public function updated(SaleAdditionalMaterial $saleAdditionalMaterial)
    {
        //
    }

    public function deleted(SaleAdditionalMaterial $saleAdditionalMaterial)
    {
    }

    public function restored(SaleAdditionalMaterial $saleAdditionalMaterial)
    {
        //
    }

    public function forceDeleted(SaleAdditionalMaterial $saleAdditionalMaterial)
    {
        //
    }
}
