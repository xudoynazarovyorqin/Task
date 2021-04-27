<?php

namespace App\Listeners\Models;

use App\DefectProduct;
use App\Events\Models\CreateDefectProductEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateDefectProductListener
{
    public function __construct()
    {
        //
    }

    public function handle(CreateDefectProductEvent $event)
    {
        if ($request = $event->getRequest()){
            $defect_product = DefectProduct::create($request);
            if ($reasons = $event->getReasons()){
                foreach ($reasons as $reason){
                    $defect_product->reasons()->create($reason);
                }
            }
        }
    }
}
