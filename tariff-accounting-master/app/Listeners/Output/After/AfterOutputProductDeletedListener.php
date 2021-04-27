<?php

namespace App\Listeners\Output\After;

use App\Events\Output\After\AfterOutputProductDeletedEvent;
use App\Reservation;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AfterOutputProductDeletedListener
{
    public function __construct()
    {
        //
    }

    public function handle(AfterOutputProductDeletedEvent $event)
    {
        if ($outputProduct = $event->getOutputProduct()){
            if ($outputProduct->sourceable_type == Reservation::TABLE_NAME){
                /**
                 * Configure warehouse product.
                 */
                try {
                    if ($warehouse_product = $outputProduct->warehouse_product){
                        $warehouse_product->remainder += $outputProduct->quantity;
                        $warehouse_product->booked += $outputProduct->quantity;
                        $warehouse_product->update();
                    };
                }
                catch (\Exception $exception){

                }
                /**
                 * Configure reservation
                 */
                try {
                    if ($reservation = $outputProduct->sourceable){
                        $reservation->issued -=$outputProduct->quantity;
                        $reservation->update();
                    }
                }catch(\Exception $exception){

                }
            }else{
                /**
                 * Configure warehouse product
                 */
                try {
                    if ($warehouse_product = $outputProduct->warehouse_product){
                        $warehouse_product->remainder += $outputProduct->quantity;
                        $warehouse_product->update();
                    };
                }catch (\Exception $exception){

                }
            }
        }
    }
}
