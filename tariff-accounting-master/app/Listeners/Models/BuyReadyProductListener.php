<?php

namespace App\Listeners\Models;

use App\BuyReadyProduct;
use App\BuyReadyProductList;
use App\Currency;
use App\Events\Models\BuyReadyProductEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BuyReadyProductListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    public function handle(BuyReadyProductEvent $event)
    {
        $buy = null;
        if ($event->isNew()){
            $buy = new BuyReadyProduct();
        }else{
            $buy = $event->getModel();
        }

        $buy['number'] = $event->getNumber();
        $buy['datetime'] = $event->getDatetime();
        $buy['provider_id'] = $event->getProviderId();
        $buy['date'] = $event->getDate();
        $buy['comment'] = $event->getComment();
        $buy['status_id'] = $event->getStatusId();
        $buy['is_warehouse'] = $event->getIsWarehouse();
        if ($event->getIsWarehouse() == 1){
            $buy['object_id'] = null;
            $buy['object_type'] = null;
        }else{
            $buy['object_id'] = $event->getObjectId();
            $buy['object_type'] = $event->getObjectType();
        }
        $buy['contract_provider_id'] = $event->getContractId();
        $buy['buy_notification_id'] = $event->getNotificationId();
        $buy->save();

        if ($items = $event->getItems()){
            foreach ($items as $item){
                BuyReadyProductList::create([
                    'buy_id'             => $buy->id,
                    'product_id'         => $item['product_id'],
                    'qty_weight'         => $item['qty_weight'],
                    'not_enough'         => $item['qty_weight'],
                    'currency_id'        => Currency::MULTI_CURRENCY ? $item['currency_id'] : 1,
                    'rate'               => Currency::MULTI_CURRENCY ? $item['rate'] : 1,
                    'buy_price'          => $item['price'],
                ]);
            }
        }
    }
}
