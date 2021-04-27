<?php

namespace App\Observers;

use App\DistributionTransaction;

class DistributionTransactionObserver
{
    /**
     * Handle the distribution transaction "created" event.
     *
     * @param  \App\DistributionTransaction  $distributionTransaction
     * @return void
     */
    public function created(DistributionTransaction $distributionTransaction)
    {
        if( $transaction = $distributionTransaction->transaction ) {
            $transaction->distribution_amount += $distributionTransaction->price;
            $transaction->update();
        }
    }

    /**
     * Handle the distribution transaction "updated" event.
     *
     * @param  \App\DistributionTransaction  $distributionTransaction
     * @return void
     */
    public function updated(DistributionTransaction $distributionTransaction)
    {
        //
    }

    /**
     * Handle the distribution transaction "deleted" event.
     *
     * @param  \App\DistributionTransaction  $distributionTransaction
     * @return void
     */
    public function deleted(DistributionTransaction $distributionTransaction)
    {
        if( $transaction = $distributionTransaction->transaction ) {
            $transaction->distribution_amount -= $distributionTransaction->price;
            $transaction->update();
        }
    }

    /**
     * Handle the distribution transaction "restored" event.
     *
     * @param  \App\DistributionTransaction  $distributionTransaction
     * @return void
     */
    public function restored(DistributionTransaction $distributionTransaction)
    {
        //
    }

    /**
     * Handle the distribution transaction "force deleted" event.
     *
     * @param  \App\DistributionTransaction  $distributionTransaction
     * @return void
     */
    public function forceDeleted(DistributionTransaction $distributionTransaction)
    {
        //
    }
}
