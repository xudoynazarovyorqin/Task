<?php

namespace App\Observers;

use App\AssemblyAdditionalMaterial;
use App\AssemblyMaterial;
use Illuminate\Support\Facades\Log;

class AssemblyAdditionalMaterialObserver
{

    public function created(AssemblyAdditionalMaterial $assemblyAdditionalMaterial)
    {
        if ($material = $assemblyAdditionalMaterial->material){
            if ($material->measurement_changeable == true){
                $rate = $material->additional_measurement_rate > 0 ? $material->additional_measurement_rate : 1;
                $assemblyAdditionalMaterial->quantity = $assemblyAdditionalMaterial->quantity / $rate;
                $assemblyAdditionalMaterial->measurement_rate = $rate;
                $assemblyAdditionalMaterial->update();
            }
        }

        if($assemblyMaterial = AssemblyMaterial::where('material_id',$assemblyAdditionalMaterial->material_id)->where('assembly_id',$assemblyAdditionalMaterial->assembly_id)->first()){
            $assemblyMaterial->update([
                'total' => $assemblyMaterial->total + $assemblyAdditionalMaterial->quantity
            ]);
        }else{
            AssemblyMaterial::create([
                'assembly_id' => $assemblyAdditionalMaterial->assembly_id,
                'material_id' => $assemblyAdditionalMaterial->material_id,
                'total'   => $assemblyAdditionalMaterial->quantity,
            ]);
        }
    }


    public function updated(AssemblyAdditionalMaterial $assemblyAdditionalMaterial)
    {
        //
    }


    public function deleted(AssemblyAdditionalMaterial $assemblyAdditionalMaterial)
    {
    }


    public function restored(AssemblyAdditionalMaterial $assemblyAdditionalMaterial)
    {
        //
    }

    public function forceDeleted(AssemblyAdditionalMaterial $assemblyAdditionalMaterial)
    {
        //
    }
}
