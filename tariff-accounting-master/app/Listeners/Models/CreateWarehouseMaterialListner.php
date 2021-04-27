<?php

namespace App\Listeners\Models;

use App\Currency;
use App\Events\Models\CreateWarehouseMaterialEvent;
use App\WarehouseMaterial;

class CreateWarehouseMaterialListner
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

    public function handle(CreateWarehouseMaterialEvent $event)
    {
        WarehouseMaterial::create([
            'material_id'    => $event->getMaterialId(),
            'remainder'      => $event->getRemainder(),
            'total_coming'   => $event->getRemainder(),
            'currency_id'    => $event->getCurrencyId() ?? Currency::DEFAULT_CURRENCY_ID,
            'rate'           => $event->getRate() ?? Currency::DEFAULT_CURRENCY_RATE,
            'warehouse_id'   => $event->getWarehouseId(),
            'buy_price'      => $event->getBuyPrice() ?? 0,
            'price'          => $event->getPrice(),
            WarehouseMaterial::ABLE_TYPE => $event->getWarehouseMaterialableType(),
            WarehouseMaterial::ABLE_ID => $event->getWarehouseMaterialableId(),
        ]);
    }
}
