<?php

namespace App\Listeners\Models;

use App\Events\Models\SaleReadyProductEvent;
use App\Events\Reservation\ReservationProductForSaleReadyProductEvent;
use App\Events\WriteOff\WriteOffProductWhenSaleReadyProductCreatedEvent;
use App\SaleReadyProduct;
use App\SaleReadyProductItem;

class SaleReadyProductListener
{
    public function __construct()
    {
        //
    }

    public function handle(SaleReadyProductEvent $event)
    {
        $sale = null;
        if ($event->isNew()){
            $sale = new SaleReadyProduct();
        }else{
            $sale = $event->getModel();
        }

        $sale['number'] = $event->getNumber();
        $sale['datetime'] = $event->getDatetime();
        $sale['contract_client_id'] = $event->getContractId();
        $sale['client_id'] = $event->getClientId();
        $sale['state_id'] = $event->getStatusId();
        $sale->save();

        if ($items = $event->getItems()){
            foreach ($items as $item){
                SaleReadyProductItem::create([
                    'sale_id'       => $sale->id,
                    'product_id'    => $item['product_id'],
                    'quantity'      => $item['quantity'],
                    'selling_price' => $item['selling_price'],
                    'currency_id'   => $item['currency_id'],
                    'rate'          => $item['rate'],
                ]);
            }
        }

        if ($event->isNew()) {
            if (setting('auto_reservation', false) === true) {
                event(new ReservationProductForSaleReadyProductEvent($sale));
            }elseif (setting('automatic_write_off',false) === true){
                event(new WriteOffProductWhenSaleReadyProductCreatedEvent($sale));
            }
        }
    }
}
