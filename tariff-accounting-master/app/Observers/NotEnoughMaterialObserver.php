<?php

namespace App\Observers;

use App\AssemblyMaterial;
use App\BuyNotification;
use App\NotEnoughMaterial;
use App\SaleMaterial;
use Illuminate\Support\Facades\DB;

class NotEnoughMaterialObserver
{
    public function created(NotEnoughMaterial $notEnoughMaterial)
    {
        if ($notify = BuyNotification::find($notEnoughMaterial->buy_notification_id)){
            if ($notify->buy_notificationable_type == BuyNotification::SALE_TYPE){
                if ($saleMaterial = SaleMaterial::where('sale_id',$notify->buy_notificationable_id)->where('material_id',$notEnoughMaterial->material_id)->first()){
                    $saleMaterial->waiting_to_buy +=$notEnoughMaterial->quantity;
                    $saleMaterial->update();
                }
            }elseif ($notify->buy_notificationable_type == BuyNotification::ASSEMBLY_TYPE){
                if ($assemblyMaterial = AssemblyMaterial::where('assembly_id',$notify->buy_notificationable_id)->where('material_id',$notEnoughMaterial->material_id)->first())
                {
                    $assemblyMaterial->waiting_to_buy +=$notEnoughMaterial->quantity;
                    $assemblyMaterial->update();
                }
            }
        }
    }


    public function updated(NotEnoughMaterial $notEnoughMaterial)
    {
        //
    }

    public function deleted(NotEnoughMaterial $notEnoughMaterial)
    {
        //
    }


    public function restored(NotEnoughMaterial $notEnoughMaterial)
    {
        //
    }


    public function forceDeleted(NotEnoughMaterial $notEnoughMaterial)
    {
        //
    }
}
