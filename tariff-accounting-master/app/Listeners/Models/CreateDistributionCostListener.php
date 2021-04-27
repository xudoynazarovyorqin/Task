<?php

namespace App\Listeners\Models;

use App\AdditionalPrice;
use App\DistributionCost;
use App\DistributionTransaction;
use App\Events\Models\CreateDistributionCostEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CreateDistributionCostListener
{
    public function __construct()
    {
        //
    }

    public function handle(CreateDistributionCostEvent $event)
    {
        $distribution_cost = DistributionCost::create([
            'datetime'  => $event->getDatetime(),
            'type'      => $event->getType(),
            'from_date' => $event->getFromDate(),
            'to_date'   => $event->getToDate(),
        ]);

        if ($additional_prices = $event->getItems()){
            if (is_array($additional_prices)){
                foreach ($additional_prices as $additional_price){
                    $additional_price_model = AdditionalPrice::create([
                        'distribution_cost_id'      => $distribution_cost->id,
                        'additional_priceable_type' => $additional_price['additional_priceable_type'],
                        'additional_priceable_id'   => $additional_price['additional_priceable_id'],
                        'price'                     => $additional_price['price'],
                    ]);
                }
            }
        }

        if ($distribution_transactions = $event->getTransactions()){
            if (is_array($distribution_transactions)){
                foreach ($distribution_transactions as $distribution_transaction){
                    $distribution_transaction_model = DistributionTransaction::create([
                        'distribution_cost_id'  => $distribution_cost->id,
                        'transaction_id'        => $distribution_transaction['transaction_id'],
                        'price'                 => $distribution_transaction['price'],
                    ]);
                }
            }
        }
    }
}
