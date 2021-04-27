<?php

namespace App\Listeners\WriteOff;

use App\Assembly;
use App\Events\Models\CreateShipmentEvent;
use App\Events\WriteOff\WriteOffProductWhenAssemblyCreatedEvent;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\WarehouseProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WriteOffProductWhenAssemblyCreatedListener
{
    public function __construct()
    {
        //
    }

    public function handle(WriteOffProductWhenAssemblyCreatedEvent $event)
    {

        $items = collect();

        if ($assembly = $event->getAssembly()){
            $assembly_products = $assembly->assembly_products;

            $on_warehouse = 0;

            foreach ($assembly_products as $assembly_product)
            {
                $not_enough_quantity = $assembly_product->total - ($assembly_product->ready + $assembly_product->waiting_to_buy);

                // TODO:: Setting for LIFO and FIFO warehouse products
                $on_warehouse = $warehouse_products = WarehouseProduct::where('product_id', $assembly_product->product_id)
                    ->whereRaw('remainder - booked > 0')
                    ->sum(DB::raw('remainder - booked'));


                if ($on_warehouse > 0){
                    $items->push([
                        'product_id' => $assembly_product->product_id,
                        'quantity'   => $not_enough_quantity > $on_warehouse ? $on_warehouse : $not_enough_quantity,
                        'issued_from_booked'   => 0,
                    ]);
                }
            }

            if (count($items)){
                $create_shipment_event = new CreateShipmentEvent();
                $create_shipment_event->setDatetime(date(Controller::ELEMENT_DATE_TIME_FORMAT,strtotime(now())));
                $create_shipment_event->setShipmentableId($assembly->id);
                $create_shipment_event->setShipmentableType(Assembly::TABLE_NAME);
                $create_shipment_event->setUserId(setting('default_warehouse_user_id',auth()->id()));
                $create_shipment_event->setItems($items->all());
                event($create_shipment_event);

            }
        }
    }
}
