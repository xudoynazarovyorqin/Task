<?php

namespace App\Observers;

use App\AssemblyItemMaterial;
use App\AssemblyMaterial;

class AssemblyItemMaterialObserver
{
    public function created(AssemblyItemMaterial $assemblyItemMaterial)
    {
        if ($material = $assemblyItemMaterial->material){
            if ($material->measurement_changeable == true){
                $assemblyItemMaterial->measurement_rate = $material->additional_measurement_rate;
                $assemblyItemMaterial->update();
            }
        }

        if($assemblyMaterial = AssemblyMaterial::where('material_id',$assemblyItemMaterial->material_id)->where('assembly_id',$assemblyItemMaterial->assembly_item->assembly_id)->first()){
            $assemblyMaterial->update([
                'total' => $assemblyMaterial->total + $assemblyItemMaterial->quantity
            ]);
        }else{
            AssemblyMaterial::create([
                'assembly_id' => $assemblyItemMaterial->assembly_item->assembly_id,
                'material_id' => $assemblyItemMaterial->material_id,
                'total'   => $assemblyItemMaterial->quantity,
                'ready'         => 0,
                'waiting_to_buy'=> 0,
            ]);
        }
    }

    public function updated(AssemblyItemMaterial $assemblyItemMaterial)
    {
        //
    }

    public function deleted(AssemblyItemMaterial $assemblyItemMaterial)
    {

    }

    public function restored(AssemblyItemMaterial $assemblyItemMaterial)
    {
        //
    }


    public function forceDeleted(AssemblyItemMaterial $assemblyItemMaterial)
    {
        //
    }
}
