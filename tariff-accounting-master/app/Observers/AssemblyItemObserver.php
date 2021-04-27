<?php

namespace App\Observers;

use App\AssemblyItem;
use App\AssemblyItemMaterial;
use App\AssemblyItemProduct;

class AssemblyItemObserver
{
    public function created(AssemblyItem $assemblyItem)
    {
        if($product = $assemblyItem->product){

            /*
             * Save assembly item materials
             */
            if($product_materials = $product->materials){
                foreach ($product_materials as $product_material){
                    AssemblyItemMaterial::create([
                        'assembly_item_id' => $assemblyItem->id,
                        'material_id'      => $product_material->material_id,
                        'quantity'         => $assemblyItem->quantity * $product_material->quantity,
                    ]);
                }
            };

            /*
             * Save assembly item products
             */
            if ($product_semi_products = $product->semi_products){
                foreach ($product_semi_products as $product_semi_product){
                    AssemblyItemProduct::create([
                        'assembly_item_id' => $assemblyItem->id,
                        'product_id'       => $product_semi_product->semi_product_id,
                        'quantity'         => $assemblyItem->quantity * $product_semi_product->quantity,
                        'ready'            => 0,
                    ]);
                }
            }

        };

    }

    public function updated(AssemblyItem $assemblyItem)
    {
        //
    }

    public function deleted(AssemblyItem $assemblyItem)
    {
        $assembly_item_materials = AssemblyItemMaterial::where('assembly_item_id',$assemblyItem->id)->get();
        $assembly_item_products = AssemblyItemProduct::where('assembly_item_id',$assemblyItem->id)->get();

        foreach ($assembly_item_materials as $assembly_item_material){
            $assembly_item_material->delete();
        }

        foreach ($assembly_item_products as $assembly_item_product){
            $assembly_item_product->delete();
        }
    }

    public function restored(AssemblyItem $assemblyItem)
    {
        //
    }

    public function forceDeleted(AssemblyItem $assemblyItem)
    {
        //
    }
}
