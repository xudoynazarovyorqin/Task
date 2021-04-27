<?php

namespace App\Observers;

use App\ContractClient;

class ContractClientObserver
{
    public function creating(ContractClient $contractClient)
    {
        //$contractClient->paid = 0;
    }

    public function created(ContractClient $contractClient)
    {

    }

    public function updated(ContractClient $contractClient)
    {
        //
    }

    public function deleted(ContractClient $contractClient)
    {
        //
    }

    public function restored(ContractClient $contractClient)
    {
        //
    }

    public function forceDeleted(ContractClient $contractClient)
    {
        //
    }
}
