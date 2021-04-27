<?php

namespace App\Listeners\GetFromWarehouse;

use App\Events\GetFromWarehouse\GetProductForShipmentProductEvent;
use App\Events\Models\CreateShipmentEvent;
use App\Events\Output\OutputProductEvent;
use App\Reservation;
use App\Shipment;
use App\ShipmentProduct;
use App\WarehouseProduct;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class GetProductForShipmentProductListener
{
    public function __construct()
    {
        //
    }

    public function handle(GetProductForShipmentProductEvent $event)
    {
        if ($shipment_product = $event->getShipmentProduct()){

            /**
             * If this shipment has get from reservation this function working.
             */
            if ($shipment_product->issued_from_booked > 0 && $shipment = $shipment_product->shipment){
                $issued_from_booked = $shipment_product->issued_from_booked;

                /**
                 * Reservations for this document
                 */
                $reservations = Reservation::where(Reservation::ABLE_TYPE,$shipment[Shipment::ABLE_TYPE])
                    ->where(Reservation::ABLE_ID,$shipment[Shipment::ABLE_ID])
                    ->whereHasMorph('sourceable',WarehouseProduct::class,function ($query) use ($shipment_product){
                        return $query->where('product_id',$shipment_product->product_id);
                    })
                    ->oldest()
                    ->get();


                foreach ($reservations as $reservation){
                    $reservation_residue = $reservation->quantity - $reservation->issued;
                    $used_quantity = ($reservation_residue >= $issued_from_booked) ? $issued_from_booked : $reservation_residue;

                    $event_output_product = new OutputProductEvent();
                    $event_output_product->setOutputProductableType(ShipmentProduct::TABLE_NAME);
                    $event_output_product->setOutputProductableId($shipment_product->id);
                    $event_output_product->setQuantity($used_quantity);
                    $event_output_product->setProductId($shipment_product->product_id);
                    $event_output_product->setWarehouseProductId($reservation->sourceable_id);
                    $event_output_product->setSourceableId($reservation->id);
                    $event_output_product->setSourceableType(Reservation::TABLE_NAME);
                    event($event_output_product);

                    $issued_from_booked = $issued_from_booked - $used_quantity;
                    if ($issued_from_booked == 0)  break;
                }
            }


            $quantity = $shipment_product->quantity;

            // TODO:: Setting for LIFO and FIFO warehouse products
            $warehouse_products = WarehouseProduct::where('product_id', $shipment_product->product_id)
                ->whereRaw('(remainder - booked) > 0')
                ->orderBy('created_at',setting('control_products_sort','asc'))
                ->get();

            foreach ($warehouse_products as $warehouse_product) {
                $wr_remainder = $warehouse_product->remainder - $warehouse_product->booked;
                $used_quantity = ($wr_remainder >= $quantity) ? $quantity : $wr_remainder;

                if ($used_quantity > 0){
                    $event_output_product = new OutputProductEvent();
                    $event_output_product->setOutputProductableId($shipment_product->id);
                    $event_output_product->setOutputProductableType(ShipmentProduct::TABLE_NAME);
                    $event_output_product->setWarehouseProductId($warehouse_product->id);
                    $event_output_product->setQuantity($used_quantity);
                    $event_output_product->setProductId($warehouse_product->product_id);
                    $event_output_product->setWarehouseproductId($warehouse_product->id);
                    event($event_output_product);
                }

                $quantity = $quantity - $used_quantity;
                if ($quantity == 0)  break;
            }

        }
    }
}
