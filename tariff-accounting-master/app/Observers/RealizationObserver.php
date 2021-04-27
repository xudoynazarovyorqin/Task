<?php

namespace App\Observers;

use App\Realization;

class RealizationObserver
{
    public function created(Realization $realization)
    {
        //
    }

    public function updated(Realization $realization)
    {
        //
    }

    public function deleted(Realization $realization)
    {
        $realization->realization_materials()->each(function ($item){
           $item->delete();
        });
    }

    public function restored(Realization $realization)
    {
        //
    }

    public function forceDeleted(Realization $realization)
    {
        //
    }
}
