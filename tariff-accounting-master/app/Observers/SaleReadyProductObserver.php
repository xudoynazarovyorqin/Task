<?php

namespace App\Observers;

use App\SaleReadyProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SaleReadyProductObserver
{
    public function creating(SaleReadyProduct $saleReadyProduct)
    {
        $saleReadyProduct->user_id = auth()->user()->id;
        if ($saleReadyProduct->paid_price == null){
            $saleReadyProduct->paid_price = 0;
        }
    }

    public function created(SaleReadyProduct $saleReadyProduct)
    {

    }


    public function updated(SaleReadyProduct $saleReadyProduct)
    {

    }

    public function deleted(SaleReadyProduct $saleReadyProduct)
    {
        $saleReadyProduct->items()->each(function ($item){
            $item->delete();
        });
    }

    public function restored(SaleReadyProduct $saleReadyProduct)
    {
        //
    }


    public function forceDeleted(SaleReadyProduct $saleReadyProduct)
    {
        //
    }
}
