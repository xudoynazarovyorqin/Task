<?php

namespace App\Listeners\Output;

use App\Events\Output\OutputMaterialEvent;
use App\OutputMaterial;
use App\Realization;
use App\Reservation;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OutputMaterialListener
{
    public function __construct()
    {
        //
    }

    public function handle(OutputMaterialEvent $event)
    {
        OutputMaterial::create([
            OutputMaterial::ABLE_ID     => $event->getOutputMateterialableId(),
            OutputMaterial::ABLE_TYPE   => $event->getOutputMateterialableType(),
            OutputMaterial::SOURCEABLE_ID => $event->getSourceableId(),
            OutputMaterial::SOURCEABLE_TYPE => $event->getSourceableType(),
            'material_id'              => $event->getMaterialId(),
            'warehouse_material_id'    => $event->getWarehouseMaterialId(),
            'quantity'                 => $event->getQuantity(),
        ]);
    }
}
