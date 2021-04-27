<?php

namespace App\Listeners\Output;

use App\Events\Output\OutputProductEvent;
use App\OutputProduct;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OutputProductListener
{
    public function __construct()
    {
        //
    }


    public function handle(OutputProductEvent $event)
    {
        OutputProduct::create([
            OutputProduct::ABLE_ID     => $event->getOutputProductableId(),
            OutputProduct::ABLE_TYPE   => $event->getOutputProductableType(),
            OutputProduct::SOURCEABLE_ID => $event->getSourceableId(),
            OutputProduct::SOURCEABLE_TYPE => $event->getSourceableType(),
            'product_id'              => $event->getProductId(),
            'warehouse_product_id'    => $event->getWarehouseProductId(),
            'quantity'                 => $event->getQuantity(),
        ]);
    }
}
