<?php

namespace App\Observers;

use App\SaleProduct;
use App\SaleProductMaterial;

class SaleProductObserver
{
    public function created(SaleProduct $saleProduct)
    {
        $product = $saleProduct->product;
        $product_materials = [];

        if ($product)
            $product_materials = $product->materials;

        foreach ($product_materials as $product_material) {
            SaleProductMaterial::create([
                'sale_product_id' => $saleProduct->id,
                'material_id'     => $product_material->material_id,
                'quantity'        => $product_material->quantity * $saleProduct->quantity,
            ]);
        }
    }
    //

    public function updated(SaleProduct $saleProduct)
    {
        //
    }

    public function deleted(SaleProduct $saleProduct)
    {
        $sale_product_materials = $saleProduct->materials;
        foreach ($sale_product_materials as $sale_product_material){
            $sale_product_material->delete();
        }
    }

    public function restored(SaleProduct $saleProduct)
    {
        //
    }

    public function forceDeleted(SaleProduct $saleProduct)
    {
        //
    }
}
