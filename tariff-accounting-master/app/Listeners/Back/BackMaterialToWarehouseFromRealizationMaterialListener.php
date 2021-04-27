<?php

namespace App\Listeners\Back;

use App\Events\Back\BackMaterialToWarehouseFromRealizationMaterialEvent;
use App\OutputMaterial;
use App\RealizationMaterial;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BackMaterialToWarehouseFromRealizationMaterialListener
{
    public function __construct()
    {
        //
    }

    public function handle(BackMaterialToWarehouseFromRealizationMaterialEvent $event)
    {
        if ($realization_material = $event->getRealizationMaterial()){
            /**
             *
             */
            $output_materials = OutputMaterial::where([
                [OutputMaterial::ABLE_ID,'=',$realization_material->id],
                [OutputMaterial::ABLE_TYPE,'=',RealizationMaterial::TABLE_NAME]
            ])->get();

            foreach ($output_materials as $output_material){
                $output_material->delete();
            }
        }
    }
}
