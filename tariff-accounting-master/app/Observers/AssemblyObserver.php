<?php

namespace App\Observers;

use App\Assembly;

class AssemblyObserver
{

    public function created(Assembly $assembly)
    {
        //
    }


    public function updated(Assembly $assembly)
    {
        //
    }


    public function deleted(Assembly $assembly)
    {
        $assembly->sales()->each(function ($sale){
           $sale->delete();
        });

        $assembly->items()->each(function ($item){
            $item->delete();
        });
    }


    public function restored(Assembly $assembly)
    {
        //
    }


    public function forceDeleted(Assembly $assembly)
    {
        //
    }
}
