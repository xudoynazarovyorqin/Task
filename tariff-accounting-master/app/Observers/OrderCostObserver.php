<?php

namespace App\Observers;

use App\OrderCost;

class OrderCostObserver
{

    public function created(OrderCost $orderCost)
    {
        if ($order = $orderCost->order){
            $order->amount += $orderCost->amount;
            $order->update();
        }
    }

    public function updated(OrderCost $orderCost)
    {
        //
    }

    public function deleted(OrderCost $orderCost)
    {
        if ($order = $orderCost->order){
            $order->amount -= $orderCost->amount;
            $order->update();
        }
    }

    public function restored(OrderCost $orderCost)
    {
        //
    }

    public function forceDeleted(OrderCost $orderCost)
    {
        //
    }
}
