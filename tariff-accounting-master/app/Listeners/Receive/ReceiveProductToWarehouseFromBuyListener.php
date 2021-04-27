<?php

namespace App\Listeners\Receive;

use App\AssemblyProduct;
use App\BuyReadyProduct;
use App\BuyReadyProductList;
use App\Events\Receive\ReceiveProductToWarehouseFromBuyEvent;
use App\OutputProduct;
use App\WarehouseProduct;

class ReceiveProductToWarehouseFromBuyListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function handle(ReceiveProductToWarehouseFromBuyEvent $event)
    {
        if ($warehouseProduct = $event->getWarehouseProduct()){
            $buy_product = BuyReadyProductList::find($warehouseProduct->warehouse_productable_id);

            $totalReceive = WarehouseProduct::where([
                ['warehouse_productable_type' ,'=', WarehouseProduct::WAREHOUSE_ABLE_TYPE_BUY_READY_PRODUCT_LISTS],
                ['warehouse_productable_id' ,'=', $buy_product->id],
                ['product_id','=', $warehouseProduct->product_id]
            ])->sum('receive');
            $buy_product->update(['not_enough' => ($buy_product->qty_weight - $totalReceive)]);

            /*
             * If this buy for assembly then send to assembly
             */
            $buy = $buy_product->buy;
            if( $buy && !$buy->is_warehouse && $buy->object_type == BuyReadyProduct::OBJECT_TYPE_ASSEMBLY)
            {
                $assemblyProduct = AssemblyProduct::where('assembly_id', $buy->object_id)->where('product_id', $warehouseProduct->product_id)->first();
                $notEnoughProductQuantity = $assemblyProduct->total - $assemblyProduct->ready;
                if( $notEnoughProductQuantity > 0 )
                {
                    OutputProduct::create([
                        'output_productable_id'      => $buy->object_id,
                        'output_productable_type'    => OutputProduct::OUTPUT_PRODUCT_TYPE_ASSEMBLIES,
                        'product_id'                 => $warehouseProduct->product_id,
                        'warehouse_product_id'       => $warehouseProduct->id,
                        'quantity'                   => ($notEnoughProductQuantity >= $warehouseProduct->receive) ? $warehouseProduct->receive : $notEnoughProductQuantity
                    ]);
                }
            }
        }
    }
}
