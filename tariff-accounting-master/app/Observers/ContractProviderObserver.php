<?php

namespace App\Observers;

use App\ContractProvider;

class ContractProviderObserver
{
    public function creating(ContractProvider $contractProvider)
    {
        $contractProvider->paid = 0;
    }

    /**
     * Handle the contract provider "created" event.
     *
     * @param  \App\ContractProvider  $contractProvider
     * @return void
     */
    public function created(ContractProvider $contractProvider)
    {

    }

    /**
     * Handle the contract provider "updated" event.
     *
     * @param  \App\ContractProvider  $contractProvider
     * @return void
     */
    public function updated(ContractProvider $contractProvider)
    {
        //
    }

    /**
     * Handle the contract provider "deleted" event.
     *
     * @param  \App\ContractProvider  $contractProvider
     * @return void
     */
    public function deleted(ContractProvider $contractProvider)
    {
        //
    }

    /**
     * Handle the contract provider "restored" event.
     *
     * @param  \App\ContractProvider  $contractProvider
     * @return void
     */
    public function restored(ContractProvider $contractProvider)
    {
        //
    }

    /**
     * Handle the contract provider "force deleted" event.
     *
     * @param  \App\ContractProvider  $contractProvider
     * @return void
     */
    public function forceDeleted(ContractProvider $contractProvider)
    {
        //
    }
}
