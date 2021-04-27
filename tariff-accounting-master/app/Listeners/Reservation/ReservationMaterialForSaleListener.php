<?php

namespace App\Listeners\Reservation;

use App\Events\Models\CreateBuyNotificationEvent;
use App\Events\Models\CreateReservationEvent;
use App\OutputMaterial;
use App\Reservation;
use App\Sale;
use App\WarehouseMaterial;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class ReservationMaterialForSaleListener
{
    public function __construct()
    {

    }

    public function handle($event)
    {
        if ($sale = $event->getSale()){
            $sale_materials = $sale->sale_materials;
            foreach ($sale_materials as $sale_material)
            {
                $not_enough_quantity = $sale_material->total - ($sale_material->ready + $sale_material->waiting_to_buy);

                // TODO:: Setting for LIFO and FIFO warehouse materials
                $warehouse_materials = WarehouseMaterial::where('material_id', $sale_material->material_id)
                    ->whereRaw('remainder - booked > 0')
                    ->orderBy('created_at',setting('control_materials_sort','asc'))
                    ->get();

                foreach ($warehouse_materials as $warehouse_material) {
                    $real_rem = $warehouse_material->remainder - $warehouse_material->booked;
                    $used_quantity = ($real_rem >= $not_enough_quantity) ? $not_enough_quantity : $real_rem;

                    $reservation_event = new CreateReservationEvent();
                    $reservation_event->setReservationableType(Sale::TABLE_NAME);
                    $reservation_event->setReservationableId($sale->id);
                    $reservation_event->setSourceableType(WarehouseMaterial::TABLE_NAME);
                    $reservation_event->setSourceableId($warehouse_material->id);
                    $reservation_event->setQuantity($used_quantity);
                    event($reservation_event);

                    $not_enough_quantity = $not_enough_quantity - $used_quantity;
                    if ($not_enough_quantity <= 0)  break;
                }
            }

            // TODO: Turned off auto notification create function.
//            $event = new CreateBuyNotificationEvent();
//            $event->setModel($sale);
//            event($event);
        }

    }
}
