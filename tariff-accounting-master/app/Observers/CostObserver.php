<?php

namespace App\Observers;

use App\Cost;

class CostObserver
{
    /**
     * Handle the cost "created" event.
     *
     * @param  \App\Cost  $cost
     * @return void
     */
    public function created(Cost $cost)
    {
        //
    }

    /**
     * Handle the cost "updated" event.
     *
     * @param  \App\Cost  $cost
     * @return void
     */
    public function updated(Cost $cost)
    {
        //
    }

    /**
     * Handle the cost "deleted" event.
     *
     * @param  \App\Cost  $cost
     * @return void
     */
    public function deleted(Cost $cost)
    {
        //
    }

    /**
     * Handle the cost "restored" event.
     *
     * @param  \App\Cost  $cost
     * @return void
     */
    public function restored(Cost $cost)
    {
        //
    }

    /**
     * Handle the cost "force deleted" event.
     *
     * @param  \App\Cost  $cost
     * @return void
     */
    public function forceDeleted(Cost $cost)
    {
        //
    }
}
