<?php

namespace App\Observers;

use App\BuyReadyProductList;
use App\Events\Receive\ReceiveProductToWarehouseFromAssemblyEvent;
use App\Events\Receive\ReceiveProductToWarehouseFromBuyEvent;
use App\Events\Receive\ReceiveProductToWarehouseFromSaleEvent;
use App\WarehouseProduct;

class WarehouseProductObserver
{
    public function created(WarehouseProduct $warehouseProduct)
    {
        if($warehouseProduct->warehouse_productable_type == WarehouseProduct::WAREHOUSE_ABLE_TYPE_BUY_READY_PRODUCT_LISTS )
        {
            event(new ReceiveProductToWarehouseFromBuyEvent($warehouseProduct));
            if ($buy_product = BuyReadyProductList::find($warehouseProduct->warehouse_productable_id)){
                $warehouseProduct->currency_id = $buy_product->currency_id;
                $warehouseProduct->buy_price = $buy_product->buy_price;
                $warehouseProduct->rate = $buy_product->rate;
                $warehouseProduct->update();
            };
        }elseif ($warehouseProduct->warehouse_productable_type == WarehouseProduct::WAREHOUSE_ABLE_TYPE_SALE_PRODUCTS){
            event(new ReceiveProductToWarehouseFromSaleEvent($warehouseProduct));
        }elseif ($warehouseProduct->warehouse_productable_type == WarehouseProduct::WAREHOUSE_ABLE_TYPE_ASSEMBLY_ITEM){
            event(new ReceiveProductToWarehouseFromAssemblyEvent($warehouseProduct));
        }
    }

    public function updated(WarehouseProduct $warehouseProduct)
    {
        //
    }

    public function deleted(WarehouseProduct $warehouseProduct)
    {
        //
    }


    public function restored(WarehouseProduct $warehouseProduct)
    {
        //
    }

    public function forceDeleted(WarehouseProduct $warehouseProduct)
    {
        //
    }
}
