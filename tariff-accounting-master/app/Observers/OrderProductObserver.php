<?php

namespace App\Observers;

use App\AssemblyItem;
use App\OrderProduct;
use App\Product;
use App\SaleProduct;

class OrderProductObserver
{
    public function created(OrderProduct $orderProduct)
    {
        if ($order = $orderProduct->order){
            $add = round($orderProduct->quantity * $orderProduct->price * $orderProduct->rate,2);
            $order->amount += $add;
            $order->update();
        }
    }

    public function updated(OrderProduct $orderProduct)
    {
        //
    }

    public function deleted(OrderProduct $orderProduct)
    {
        if ($order = $orderProduct->order){
            if ($order->production_type == Product::PRODUCTION){
                if($sale_product = SaleProduct::where('order_product_id',$orderProduct->id)->first()){
                    $sale_product->delete();
                }
            }elseif($order->production_type == Product::ASSEMBLY){
                if ($assembly_item = AssemblyItem::where('order_product_id',$orderProduct->id)->first()){
                    $assembly_item->delete();
                }
            }
            if ($order = $orderProduct->order){
                $min = round($orderProduct->quantity * $orderProduct->price * $orderProduct->rate,2);
                $order->amount -= $min;
                $order->update();
            }
        }
    }

    public function restored(OrderProduct $orderProduct)
    {
        //
    }

    public function forceDeleted(OrderProduct $orderProduct)
    {
        //
    }
}
