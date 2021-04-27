<?php

namespace App\Observers;

use App\SaleCost;

class SaleCostObserver
{
    /**
     * Handle the sale cost "created" event.
     *
     * @param  \App\SaleCost  $saleCost
     * @return void
     */
    public function created(SaleCost $saleCost)
    {
        if ($sale = $saleCost->sale){
            $sale->total_amount += $saleCost->amount;
            $sale->update();
        }
    }

    /**
     * Handle the sale cost "updated" event.
     *
     * @param  \App\SaleCost  $saleCost
     * @return void
     */
    public function updated(SaleCost $saleCost)
    {
        //
    }

    /**
     * Handle the sale cost "deleted" event.
     *
     * @param  \App\SaleCost  $saleCost
     * @return void
     */
    public function deleted(SaleCost $saleCost)
    {
        if ($sale = $saleCost->sale){
            $sale->total_amount -= $saleCost->amount;
            $sale->update();
        }
    }

    /**
     * Handle the sale cost "restored" event.
     *
     * @param  \App\SaleCost  $saleCost
     * @return void
     */
    public function restored(SaleCost $saleCost)
    {
        //
    }

    /**
     * Handle the sale cost "force deleted" event.
     *
     * @param  \App\SaleCost  $saleCost
     * @return void
     */
    public function forceDeleted(SaleCost $saleCost)
    {
        //
    }
}
