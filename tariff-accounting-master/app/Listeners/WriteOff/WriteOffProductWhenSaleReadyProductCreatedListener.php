<?php

namespace App\Listeners\WriteOff;

use App\Events\WriteOff\WriteOffProductWhenSaleReadyProductCreatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\SaleReadyProduct;
use App\Http\Controllers\Controller;
use App\WarehouseProduct;
use Illuminate\Support\Facades\DB;
use App\Events\Models\CreateShipmentEvent;

class WriteOffProductWhenSaleReadyProductCreatedListener
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

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(WriteOffProductWhenSaleReadyProductCreatedEvent $event)
    {
        $items = collect();

        if ($sale = $event->getSale()){
            $sale_products = $sale->items;

            $on_warehouse = 0;

            foreach ($sale_products as $sale_product)
            {
                $not_enough_quantity = $sale_product->quantity - $sale_product->shipped;

                // TODO:: Setting for LIFO and FIFO warehouse products
                $on_warehouse = $warehouse_products = WarehouseProduct::where('product_id', $sale_product->product_id)
                    ->whereRaw('remainder - booked > 0')
                    ->sum(DB::raw('remainder - booked'));


                if ($on_warehouse > 0){
                    $items->push([
                        'product_id' => $sale_product->product_id,
                        'quantity'   => $not_enough_quantity > $on_warehouse ? $on_warehouse : $not_enough_quantity,
                        'issued_from_booked'   => 0,
                    ]);
                }
            }

            if (count($items)){
                $create_shipment_event = new CreateShipmentEvent();
                $create_shipment_event->setDatetime(date(Controller::ELEMENT_DATE_TIME_FORMAT,strtotime(now())));
                $create_shipment_event->setShipmentableId($sale->id);
                $create_shipment_event->setShipmentableType(SaleReadyProduct::TABLE_NAME);
                $create_shipment_event->setUserId(setting('default_warehouse_user_id',auth()->id()));
                $create_shipment_event->setItems($items->all());
                event($create_shipment_event);

            }
        }
    }
}
