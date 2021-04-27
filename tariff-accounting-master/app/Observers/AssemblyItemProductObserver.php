<?php

namespace App\Observers;

use App\AssemblyItemProduct;
use App\AssemblyProduct;

class AssemblyItemProductObserver
{
    public function created(AssemblyItemProduct $assemblyItemProduct)
    {
        if($assemblyProduct = AssemblyProduct::where('product_id',$assemblyItemProduct->product_id)->where('assembly_id',$assemblyItemProduct->assembly_item->assembly_id)->first()){
            $assemblyProduct->update([
                'total' => $assemblyProduct->total + $assemblyItemProduct->quantity
            ]);
        }else{
            AssemblyProduct::create([
                'assembly_id' => $assemblyItemProduct->assembly_item->assembly_id,
                'product_id'  => $assemblyItemProduct->product_id,
                'total'       => $assemblyItemProduct->quantity,
                'ready'       => 0,
                'waiting_to_buy'=> 0,
            ]);
        }
    }


    public function updated(AssemblyItemProduct $assemblyItemProduct)
    {
        //
    }


    public function deleted(AssemblyItemProduct $assemblyItemProduct)
    {
    }


    public function restored(AssemblyItemProduct $assemblyItemProduct)
    {
        //
    }


    public function forceDeleted(AssemblyItemProduct $assemblyItemProduct)
    {
        //
    }
}
