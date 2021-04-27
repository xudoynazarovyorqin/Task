<?php

namespace App\Observers;

use App\Material;
use Illuminate\Support\Facades\DB;

class MaterialObserver
{
    /**
     * Handle the material "created" event.
     *
     * @param  \App\Material  $material
     * @return void
     */
    public function created(Material $material)
    {
        //
    }

    /**
     * Handle the material "updated" event.
     *
     * @param  \App\Material  $material
     * @return void
     */
    public function updated(Material $material)
    {
        //
    }

    /**
     * Handle the material "deleted" event.
     *
     * @param  \App\Material  $material
     * @return void
     */
    public function deleted(Material $material)
    {
        DB::table('product_materials')->where('material_id',$material->id)->delete();
    }

    /**
     * Handle the material "restored" event.
     *
     * @param  \App\Material  $material
     * @return void
     */
    public function restored(Material $material)
    {
        //
    }

    /**
     * Handle the material "force deleted" event.
     *
     * @param  \App\Material  $material
     * @return void
     */
    public function forceDeleted(Material $material)
    {
        //
    }
}
