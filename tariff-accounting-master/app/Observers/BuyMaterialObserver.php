<?php

namespace App\Observers;

use App\Buy;
use App\BuyMaterial;

class BuyMaterialObserver
{
    public function created(BuyMaterial $buyMaterial)
    {
        if ($buy = $buyMaterial->buy)
        {
            $add =  round(floatval($buyMaterial->qty_weight) * floatval($buyMaterial->price) * floatval($buyMaterial->rate),2);
            $buy->total_price +=$add;
            $buy->update();
        }
    }

    public function updated(BuyMaterial $buyMaterial)
    {
        //
    }

    public function deleted(BuyMaterial $buyMaterial)
    {
        if ($buy = $buyMaterial->buy)
        {
            $min =  round(floatval($buyMaterial->qty_weight) * floatval($buyMaterial->price) * floatval($buyMaterial->rate),2);
            $buy->total_price -=$min;
            $buy->update();
        }
    }

    public function restored(BuyMaterial $buyMaterial)
    {
        //
    }

    public function forceDeleted(BuyMaterial $buyMaterial)
    {
        //
    }
}
