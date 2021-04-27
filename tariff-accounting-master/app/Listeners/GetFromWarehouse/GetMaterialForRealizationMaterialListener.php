<?php

namespace App\Listeners\GetFromWarehouse;

use App\Events\GetFromWarehouse\GetMaterialForRealizationMaterialEvent;
use App\Events\Output\OutputMaterialEvent;
use App\OutputMaterial;
use App\Realization;
use App\RealizationMaterial;
use App\Reservation;
use App\WarehouseMaterial;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GetMaterialForRealizationMaterialListener
{
    public function __construct()
    {
        //
    }

    public function handle(GetMaterialForRealizationMaterialEvent $event)
    {
        if ($realization_material = $event->getRealizationMaterial()){
            /**
             * If this realization has get from reservation this function working.
             */
            if ($realization_material->issued_from_booked > 0 && $realization = $realization_material->realization){
                $issued_from_booked = $realization_material->issued_from_booked;

                /**
                 * Reservations for this document
                 */
                $reservations = Reservation::where(Reservation::ABLE_TYPE,$realization[Realization::ABLE_TYPE])
                    ->where(Reservation::ABLE_ID,$realization[Realization::ABLE_ID])
                    ->whereHasMorph('sourceable',WarehouseMaterial::class,function ($query) use ($realization_material){
                        return $query->where('material_id',$realization_material->material_id);
                    })
                    ->oldest()
                    ->get();

                foreach ($reservations as $reservation){
                    $reservation_residue = $reservation->quantity - $reservation->issued;
                    $used_quantity = ($reservation_residue >= $issued_from_booked) ? $issued_from_booked : $reservation_residue;

                    $event_output_material = new OutputMaterialEvent();
                    $event_output_material->setOutputMateterialableType(RealizationMaterial::TABLE_NAME);
                    $event_output_material->setOutputMateterialableId($realization_material->id);
                    $event_output_material->setQuantity($used_quantity);
                    $event_output_material->setMaterialId($realization_material->material_id);
                    $event_output_material->setWarehouseMaterialId($reservation->sourceable_id);
                    $event_output_material->setSourceableId($reservation->id);
                    $event_output_material->setSourceableType(Reservation::TABLE_NAME);
                    event($event_output_material);

                    $issued_from_booked = $issued_from_booked - $used_quantity;
                    if ($issued_from_booked == 0)  break;
                }
            }


            $quantity = $realization_material->quantity;

            // TODO:: Setting for LIFO and FIFO warehouse materials
            $warehouse_materials = WarehouseMaterial::where('material_id', $realization_material->material_id)
                ->whereRaw('(remainder - booked) > 0')
                ->orderBy('created_at',setting('control_materials_sort','asc'))
                ->get();

            foreach ($warehouse_materials as $warehouse_material) {
                $wr_remainder = $warehouse_material->remainder - $warehouse_material->booked;
                $used_quantity = ($wr_remainder >= $quantity) ? $quantity : $wr_remainder;

                if ($used_quantity > 0){
                    $event_output_material = new OutputMaterialEvent();
                    $event_output_material->setOutputMateterialableType(RealizationMaterial::TABLE_NAME);
                    $event_output_material->setOutputMateterialableId($realization_material->id);
                    $event_output_material->setQuantity($used_quantity);
                    $event_output_material->setMaterialId($warehouse_material->material_id);
                    $event_output_material->setWarehouseMaterialId($warehouse_material->id);
                    event($event_output_material);
                }

                $quantity = $quantity - $used_quantity;
                if ($quantity == 0)  break;
            }

        }
    }
}
