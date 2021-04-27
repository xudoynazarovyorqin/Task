<?php

namespace App\Observers;

use App\SaleReadyProductItem;

class SaleReadyProductItemObserver
{
    public function created(SaleReadyProductItem $saleReadyProductItem)
    {
        if ($sale = $saleReadyProductItem->sale){
            $add = round( ($saleReadyProductItem->quantity * $saleReadyProductItem->selling_price * $saleReadyProductItem->rate),2);
            $sale->total_price += $add;
            $sale->update();
        }
    }

    public function updated(SaleReadyProductItem $saleReadyProductItem)
    {
        //
    }

    public function deleted(SaleReadyProductItem $saleReadyProductItem)
    {
        if ($sale = $saleReadyProductItem->sale){
            $min = round( ($saleReadyProductItem->quantity * $saleReadyProductItem->selling_price * $saleReadyProductItem->rate),2);
            $sale->total_price -= $min;
            $sale->update();
        }
    }


    public function restored(SaleReadyProductItem $saleReadyProductItem)
    {
        //
    }

    public function forceDeleted(SaleReadyProductItem $saleReadyProductItem)
    {
        //
    }
}
