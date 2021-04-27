<?php

namespace App\Listeners\Output\After;

use App\Assembly;
use App\AssemblyMaterial;
use App\Events\Output\After\AfterOutputMaterialCreatedEvent;
use App\Reservation;
use App\Sale;
use App\SaleMaterial;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AfterOutputMaterialCreatedListener
{
    public function __construct()
    {
        //
    }

    public function handle(AfterOutputMaterialCreatedEvent $event)
    {
        if ($outputMaterial = $event->getOutputMaterial()){
            if ($outputMaterial->sourceable_type == Reservation::TABLE_NAME){
                /**
                 * Configure warehouse material.
                 */
                try {
                    if ($warehouse_material = $outputMaterial->warehouse_material){
                        $warehouse_material->remainder -= $outputMaterial->quantity;
                        $warehouse_material->booked -= $outputMaterial->quantity;
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
                        $reservation->issued +=$outputMaterial->quantity;
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
                        $warehouse_material->remainder -= $outputMaterial->quantity;
                        $warehouse_material->update();
                    };
                }catch (\Exception $exception){

                }
            }
        }
    }
}
