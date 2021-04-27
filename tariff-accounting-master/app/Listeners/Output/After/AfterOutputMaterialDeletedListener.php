<?php

namespace App\Listeners\Output\After;

use App\Events\Output\After\AfterOutputMaterialCreatedEvent;
use App\Events\Output\After\AfterOutputMaterialDeletedEvent;
use App\Reservation;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AfterOutputMaterialDeletedListener
{

    public function __construct()
    {
        //
    }

    public function handle(AfterOutputMaterialDeletedEvent $event)
    {
        if ($outputMaterial = $event->getOutputMaterial()){
            if ($outputMaterial->sourceable_type == Reservation::TABLE_NAME){
                /**
                 * Configure warehouse material.
                 */
                try {
                    if ($warehouse_material = $outputMaterial->warehouse_material){
                        $warehouse_material->remainder += $outputMaterial->quantity;
                        $warehouse_material->booked += $outputMaterial->quantity;
                        $warehouse_material->update();
                    };
                }
                catch (\Exception $exception){

                }
                /**
                 * Configure reservation
                 */
                try {
                    if ($reservation = $outputMaterial->sourceable){
                        $reservation->issued -=$outputMaterial->quantity;
                        $reservation->update();
                    }
                }catch(\Exception $exception){

                }
            }else{
                /**
                 * Configure warehouse material
                 */
                try {
                    if ($warehouse_material = $outputMaterial->warehouse_material){
                        $warehouse_material->remainder += $outputMaterial->quantity;
                        $warehouse_material->update();
                    };
                }catch (\Exception $exception){

                }
            }
        }
    }
}
