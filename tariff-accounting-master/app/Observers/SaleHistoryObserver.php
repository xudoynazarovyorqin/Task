<?php

namespace App\Observers;

use App\SaleHistory;

class SaleHistoryObserver
{
    /**
     * Handle the sale history "created" event.
     *
     * @param  \App\SaleHistory  $saleHistory
     * @return void
     */
    public function created(SaleHistory $saleHistory)
    {
        if ($sale = $saleHistory->sale){
            $sale->level_id = $saleHistory->level_id;
            $sale->update();
        }
    }

    /**
     * Handle the sale history "updated" event.
     *
     * @param  \App\SaleHistory  $saleHistory
     * @return void
     */
    public function updated(SaleHistory $saleHistory)
    {
        //
    }

    /**
     * Handle the sale history "deleted" event.
     *
     * @param  \App\SaleHistory  $saleHistory
     * @return void
     */
    public function deleted(SaleHistory $saleHistory)
    {
        //
    }

    /**
     * Handle the sale history "restored" event.
     *
     * @param  \App\SaleHistory  $saleHistory
     * @return void
     */
    public function restored(SaleHistory $saleHistory)
    {
        //
    }

    /**
     * Handle the sale history "force deleted" event.
     *
     * @param  \App\SaleHistory  $saleHistory
     * @return void
     */
    public function forceDeleted(SaleHistory $saleHistory)
    {
        //
    }
}
