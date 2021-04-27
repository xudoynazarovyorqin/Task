<?php

namespace App\Listeners\Receive;

use App\AssemblyProduct;
use App\Events\Receive\ReceiveProductToWarehouseFromSaleEvent;
use App\OutputProduct;
use App\Sale;
use App\SaleProduct;
use App\WarehouseProduct;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReceiveProductToWarehouseFromSaleListener
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


    public function handle(ReceiveProductToWarehouseFromSaleEvent $event)
    {
        if ($warehouseProduct = $event->getWarehouseProduct()){
            if( $sale_product = SaleProduct::find($warehouseProduct->warehouse_productable_id) )
            {
                $totalReceive = WarehouseProduct::where([
                    ['warehouse_productable_type' ,'=', WarehouseProduct::WAREHOUSE_ABLE_TYPE_SALE_PRODUCTS],
                    ['warehouse_productable_id' ,'=', $sale_product->id],
                    ['product_id','=', $warehouseProduct->product_id]
                ])->sum('receive');
                $sale_product->update(['ready'    => $totalReceive]);


                if( $sale = $sale_product->sale )
                {
                    if( $sale->saleable_type == Sale::SALE_TYPE_ORDERS && $sale_product->order_product )
                    {
                        $order_product = $sale_product->order_product;
                        $order_product->update([
                            'ready'    => $order_product->ready + $warehouseProduct->receive,
                        ]);
                    }
                    elseif( $sale->saleable_type == Sale::SALE_TYPE_ASSEMBLY )
                    {
                        $assemblyProduct = AssemblyProduct::where('assembly_id', $sale->saleable_id)->where('product_id', $warehouseProduct->product_id)->first();

                        $not_enough_to_assembly_product = $assemblyProduct->total - $assemblyProduct->ready;
                        if( $not_enough_to_assembly_product > 0 )
                        {
                            OutputProduct::create([
                                'output_productable_id'      => $sale->saleable_id,
                                'output_productable_type'    => OutputProduct::OUTPUT_PRODUCT_TYPE_ASSEMBLIES,
                                'product_id'                 => $warehouseProduct->product_id,
                                'warehouse_product_id'       => $warehouseProduct->id,
                                'quantity'                   => ($not_enough_to_assembly_product >= $warehouseProduct->receive) ? $warehouseProduct->receive : $not_enough_to_assembly_product
                            ]);
                        }
                    }
                }
            }
        }
    }
}
