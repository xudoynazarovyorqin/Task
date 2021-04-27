<?php

namespace App\Listeners\Receive;

use App\Assembly;
use App\AssemblyItem;
use App\Events\Receive\ReceiveProductToWarehouseFromAssemblyEvent;

class ReceiveProductToWarehouseFromAssemblyListener
{

    public function __construct()
    {
        //
    }

    public function handle(ReceiveProductToWarehouseFromAssemblyEvent $event)
    {
        if ($warehouseProduct = $event->getWarehouseProduct()){
            if( $assembly_item = AssemblyItem::find($warehouseProduct->warehouse_productable_id) )
            {
                $assembly_item->update([
                    'ready'    => $assembly_item->ready + $warehouseProduct->receive,
                ]);
                if( $assembly = $assembly_item->assembly )
                {
                    if( $assembly->assemblyable_type == Assembly::ASSEMBLY_TYPE_ORDERS && $assembly_item->order_product )
                    {
                        $order_product = $assembly_item->order_product;
                        $order_product->update([
                            'ready'    => $order_product->ready + $warehouseProduct->receive,
                        ]);
                    }
                }
            }
        }
    }
}
