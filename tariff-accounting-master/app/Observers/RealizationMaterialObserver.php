<?php

namespace App\Observers;

use App\Events\Back\BackMaterialToWarehouseFromRealizationMaterialEvent;
use App\Events\GetFromWarehouse\GetMaterialForRealizationMaterialEvent;
use App\RealizationMaterial;

class RealizationMaterialObserver
{
    public function created(RealizationMaterial $realizationMaterial)
    {
        event(new GetMaterialForRealizationMaterialEvent($realizationMaterial));
    }

    public function updated(RealizationMaterial $realizationMaterial)
    {
        //
    }

    public function deleted(RealizationMaterial $realizationMaterial)
    {
        event(new BackMaterialToWarehouseFromRealizationMaterialEvent($realizationMaterial));
    }

    public function restored(RealizationMaterial $realizationMaterial)
    {
        //
    }

    public function forceDeleted(RealizationMaterial $realizationMaterial)
    {
        //
    }
}
