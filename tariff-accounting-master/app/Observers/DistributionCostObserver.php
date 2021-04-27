<?php

namespace App\Observers;

use App\DistributionCost;

class DistributionCostObserver
{
    public function creating(DistributionCost $distributionCost)
    {
        $distributionCost->user_id = auth()->user()->id;
    }

    /**
     * Handle the distribution cost "created" event.
     *
     * @param  \App\DistributionCost  $distributionCost
     * @return void
     */
    public function created(DistributionCost $distributionCost)
    {
        //
    }

    public function updating(DistributionCost $distributionCost)
    {
        $distributionCost->user_id = auth()->user()->id;
    }

    /**
     * Handle the distribution cost "updated" event.
     *
     * @param  \App\DistributionCost  $distributionCost
     * @return void
     */
    public function updated(DistributionCost $distributionCost)
    {
        //
    }

    /**
     * Handle the distribution cost "deleted" event.
     *
     * @param  \App\DistributionCost  $distributionCost
     * @return void
     */
    public function deleted(DistributionCost $distributionCost)
    {
        $distributionCost->additional_prices()->each(function ($item){
            $item->delete();
        });

        $distributionCost->distribution_transactions->each(function ($item){
            $item->delete();
        });
    }

    /**
     * Handle the distribution cost "restored" event.
     *
     * @param  \App\DistributionCost  $distributionCost
     * @return void
     */
    public function restored(DistributionCost $distributionCost)
    {
        //
    }

    /**
     * Handle the distribution cost "force deleted" event.
     *
     * @param  \App\DistributionCost  $distributionCost
     * @return void
     */
    public function forceDeleted(DistributionCost $distributionCost)
    {
        //
    }
}
