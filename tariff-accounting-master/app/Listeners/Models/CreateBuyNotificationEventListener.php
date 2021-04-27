<?php

namespace App\Listeners\Models;

use App\Assembly;
use App\AssemblyMaterial;
use App\BuyNotification;
use App\Events\Models\CreateBuyNotificationEvent;
use App\NotEnoughMaterial;
use App\Sale;
use App\SaleMaterial;
use Illuminate\Support\Facades\Log;

class CreateBuyNotificationEventListener
{

    public function __construct()
    {
        //
    }

    public function handle(CreateBuyNotificationEvent $event)
    {
        if ($model = $event->getModel()){
            $array = [];
            $type = '';

            if (get_class($model) == Assembly::class){
                $array = AssemblyMaterial::where('assembly_id',$model->id)->whereRaw('total - (ready + waiting_to_buy) > 0')->get();
                $type = BuyNotification::ASSEMBLY_TYPE;
            }elseif (get_class($model) == Sale::class){
                $array = SaleMaterial::where('sale_id',$model->id)->whereRaw('total - (ready + waiting_to_buy) > 0')->get();
                $type = BuyNotification::SALE_TYPE;
            }

            if (count($array) > 0){
                $notify = BuyNotification::create([
                    'buy_notificationable_id' => $model->id,
                    'buy_notificationable_type' => $type,
                    'body'   => "Сырьё не хватает на складу",
                    'status' => BuyNotification::CREATED
                ]);
                foreach ($array as $item){
                    NotEnoughMaterial::create([
                        'buy_notification_id' => $notify->id,
                        'material_id'        => $item->material_id,
                        'quantity'           => $item->total - ($item->ready + $item->waiting_to_buy)
                    ]);
                }
            }
        }

    }
}
