<?php

namespace App\Listeners\Reservation;

use App\Assembly;
use App\Events\Models\CreateReservationEvent;
use App\Events\Models\CreateSaleForAssemblyEvent;
use App\Events\Reservation\ReservationProductForAssemblyEvent;
use App\WarehouseProduct;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReservationProductForAssemblyListener
{
    public function __construct()
    {
        //
    }

    public function handle(ReservationProductForAssemblyEvent $event)
    {
        if ($assembly = $event->getAssembly()){
            $assembly_products = $assembly->assembly_products;

            foreach ($assembly_products as $assembly_product)
            {
                $not_enough_quantity = $assembly_product->total - ($assembly_product->ready + $assembly_product->waiting_to_buy);

                // TODO:: Setting for LIFO and FIFO warehouse products
                $warehouse_products = WarehouseProduct::where('product_id', $assembly_product->product_id)
                    ->whereRaw('remainder - booked > 0')
                    ->orderBy('created_at',setting('control_products_sort','asc'))
                    ->get();
                foreach ($warehouse_products as $warehouse_product) {
                    $real_rem  = $warehouse_product->remainder - $warehouse_product->booked;
                    $used_quantity = ($real_rem >= $not_enough_quantity) ? $not_enough_quantity : $real_rem;

                    $reservation_event = new CreateReservationEvent();
                    $reservation_event->setReservationableType(Assembly::TABLE_NAME);
                    $reservation_event->setReservationableId($assembly->id);
                    $reservation_event->setSourceableType(WarehouseProduct::TABLE_NAME);
                    $reservation_event->setSourceableId($warehouse_product->id);
                    $reservation_event->setQuantity($used_quantity);
                    event($reservation_event);

                    $not_enough_quantity = $not_enough_quantity - $used_quantity;
                    if ($not_enough_quantity == 0)   break;
                }
            }

            // TODO:: run CreateBuyReadyProductNotificationEvent run
//            $event = new CreateBuyReadyProductNotificationEvent();
//            $event->setModel($assembly);
//            event($event);

            // TODO:: run  CreateSaleForAssemblyEvent run
//            event(new CreateSaleForAssemblyEvent($assembly));
        }
    }
}
