<?php

namespace App\Observers;

use App\SaleReadyProductList;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SaleReadyProductListObserver
{
    /**
     * Handle the sale ready product list "created" event.
     *
     * @param  \App\SaleReadyProductList  $saleReadyProductList
     * @return void
     */
    public function created(SaleReadyProductList $saleReadyProductList)
    {        
        if ($sale = $saleReadyProductList->sale)
        {
            $sale->total_price = $sale->total_price + $saleReadyProductList->total_price;
            $sale->update();
        }

        // sale uchun skladdan olgan produktni sonini ayirib yozib qoyish
        if ($warehouse_product = $saleReadyProductList->warehouse_product)
        {
            $warehouse_product->remainder -= $saleReadyProductList->quantity;
            $warehouse_product->update();
        };
    }

    /**
     * Handle the sale ready product list "updated" event.
     *
     * @param  \App\SaleReadyProductList  $saleReadyProductList
     * @return void
     */
    public function updated(SaleReadyProductList $saleReadyProductList)
    {
        //
    }

    /**
     * Handle the sale ready product list "deleted" event.
     *
     * @param  \App\SaleReadyProductList  $saleReadyProductList
     * @return void
     */
    public function deleted(SaleReadyProductList $saleReadyProductList)
    {
        if ($sale = $saleReadyProductList->sale)
        {
            $sale->total_price = $sale->total_price - $saleReadyProductList->total_price;
            $sale->update();
        }

        // agar sale dagi produkt ochsa skladga qaytib yozib qoyish
        if ($warehouse_product = $saleReadyProductList->warehouse_product)
        {
            $warehouse_product->remainder += $saleReadyProductList->quantity;
            $warehouse_product->update();
        };
    }

    /**
     * Handle the sale ready product list "restored" event.
     *
     * @param  \App\SaleReadyProductList  $saleReadyProductList
     * @return void
     */
    public function restored(SaleReadyProductList $saleReadyProductList)
    {
        //
    }

    /**
     * Handle the sale ready product list "force deleted" event.
     *
     * @param  \App\SaleReadyProductList  $saleReadyProductList
     * @return void
     */
    public function forceDeleted(SaleReadyProductList $saleReadyProductList)
    {
        //
    }
}
