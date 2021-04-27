<?php

namespace App\Listeners\Reservation;

use App\Assembly;
use App\Events\Models\CreateBuyNotificationEvent;
use App\Events\Models\CreateReservationEvent;
use App\Events\Reservation\ReservationMaterialForAssemblyEevent;
use App\OutputMaterial;
use App\WarehouseMaterial;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReservationMaterialForAssemblyListener
{
    public function __construct()
    {
        //
    }

    public function handle(ReservationMaterialForAssemblyEevent $event)
    {
        if ($assembly = $event->getAssembly()){
            $assembly_materials = $assembly->assembly_materials;

            foreach ($assembly_materials as $assembly_material)
            {
                $not_enough_quantity = $assembly_material->total - ($assembly_material->ready + $assembly_material->waiting_to_buy);

                //TODO:: Setting for LIFO and FIFO warehouse materials
                $warehouse_materials = WarehouseMaterial::where('material_id', $assembly_material->material_id)
                    ->whereRaw('remainder - booked > 0')
                    ->orderBy('created_at',setting('control_materials_sort','asc'))
                    ->get();

                foreach ($warehouse_materials as $warehouse_material) {
                    $real_rem = $warehouse_material->remainder - $warehouse_material->booked;

                    $used_quantity = ($real_rem >= $not_enough_quantity) ? $not_enough_quantity : $real_rem;

                    $reservation_event = new CreateReservationEvent();
                    $reservation_event->setReservationableType(Assembly::TABLE_NAME);
                    $reservation_event->setReservationableId($assembly->id);
                    $reservation_event->setSourceableType(WarehouseMaterial::TABLE_NAME);
                    $reservation_event->setSourceableId($warehouse_material->id);
                    $reservation_event->setQuantity($used_quantity);
                    event($reservation_event);

                    $not_enough_quantity = $not_enough_quantity - $used_quantity;
                    if ($not_enough_quantity == 0)  break;
                }
            }

            //TODO:: turned off CreateBuyNotificationEvent()
//            $event = new CreateBuyNotificationEvent();
//            $event->setModel($assembly);
//            event($event);
        }
    }
}
