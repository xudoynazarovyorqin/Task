<?php

namespace App\Observers;

use App\Order;
use App\Product;

class OrderObserver
{

    public function creating(Order $order){
    }

    public function created(Order $order)
    {
        //
    }


    public function updated(Order $order)
    {
        //
    }


    public function deleted(Order $order)
    {
        if ($order->production_type == Product::ASSEMBLY){
            $order->assemblies()->each(function ($assembly){
               $assembly->delete();
            });
        }elseif ($order->production_type == Product::ASSEMBLY){
            $order->sales()->each(function ($sale){
               $sale->delete();
            });
        }
    }


    public function restored(Order $order)
    {
        //
    }


    public function forceDeleted(Order $order)
    {
        //
    }
}
