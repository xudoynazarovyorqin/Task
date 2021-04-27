<?php

namespace App\Listeners\Models;

use App\Currency;
use App\Events\Models\CreateWarehouseProductEvent;
use App\WarehouseProduct;

class CreateWarehouseProductListener
{

    public function __construct()
    {
        //
    }

    public function handle(CreateWarehouseProductEvent $event)
    {
            WarehouseProduct::create([
               'product_id'     => $event->getProductId(),
               'remainder'      => $event->getRemainder(),
               'currency_id'    => $event->getCurrencyId() ?? Currency::DEFAULT_CURRENCY_ID,
               'rate'           => $event->getRate() ?? Currency::DEFAULT_CURRENCY_RATE,
               'warehouse_id'   => $event->getWarehouseId(),
               'buy_price'      => $event->getBuyPrice() ?? 0,
               'selling_price'  => $event->getSellingPrice(),
               'receive'        => $event->getReceive(),
               'warehouse_productable_type' => $event->getWarehouseProductableType(),
               'warehouse_productable_id' => $event->getWarehouseProductableId(),
               'owner'          => $event->getOwner(),
               'agentable_type' => $event->getAgentableType(),
               'agentable_id'   => $event->getAgentableId(),
            ]);
    }
}
