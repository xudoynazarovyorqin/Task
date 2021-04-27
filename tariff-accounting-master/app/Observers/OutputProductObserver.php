<?php

namespace App\Observers;

use App\AssemblyProduct;
use App\Events\Output\After\AfterOutputProductCreatedEvent;
use App\Events\Output\After\AfterOutputProductDeletedEvent;
use App\OutputProduct;
use App\WarehouseProduct;

class OutputProductObserver
{

    public function created(OutputProduct $outputProduct)
    {
        event(new AfterOutputProductCreatedEvent($outputProduct));
    }


    public function updating(OutputProduct $outputProduct)
    {
    }

    public function updated(OutputProduct $outputProduct)
    {
        //
    }


    public function deleted(OutputProduct $outputProduct)
    {
        event(new AfterOutputProductDeletedEvent($outputProduct));
    }


    public function restored(OutputProduct $outputProduct)
    {
        //
    }


    public function forceDeleted(OutputProduct $outputProduct)
    {
        //
    }
}
