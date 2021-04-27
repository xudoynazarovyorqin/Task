<?php

namespace App\Observers;

use App\Assembly;
use App\AssemblyMaterial;
use App\Events\Output\After\AfterOutputMaterialCreatedEvent;
use App\Events\Output\After\AfterOutputMaterialDeletedEvent;
use App\OutputMaterial;
use App\Sale;
use App\SaleMaterial;
use App\WarehouseMaterial;
use Illuminate\Support\Facades\Log;

class OutputMaterialObserver
{
    public function created(OutputMaterial $outputMaterial)
    {
        event(new AfterOutputMaterialCreatedEvent($outputMaterial));
    }

    public function updating(OutputMaterial $outputMaterial)
    {
    }

    public function updated(OutputMaterial $outputMaterial)
    {
    }

    public function deleted(OutputMaterial $outputMaterial)
    {
        event(new AfterOutputMaterialDeletedEvent($outputMaterial));
    }

    public function restored(OutputMaterial $outputMaterial)
    {
        //
    }

    public function forceDeleted(OutputMaterial $outputMaterial)
    {
        //
    }
}
