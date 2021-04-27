<?php

namespace App\Observers;

use App\ProductMaterial;

class ProductMaterialObserver
{
    public function created(ProductMaterial $productMaterial)
    {
        // TODO:: setting for additional measurement rate product material.
        if ($material = $productMaterial->material){
            if ($material->measurement_changeable == true){
                $rate = $material->additional_measurement_rate > 0 ? $material->additional_measurement_rate : 1;
                $productMaterial->quantity = $productMaterial->inverse_quantity / $rate;
                $productMaterial->update();
            }
        }
    }

    public function updated(ProductMaterial $productMaterial)
    {
        //
    }


    public function deleted(ProductMaterial $productMaterial)
    {
        //
    }


    public function restored(ProductMaterial $productMaterial)
    {
        //
    }


    public function forceDeleted(ProductMaterial $productMaterial)
    {
        //
    }
}
