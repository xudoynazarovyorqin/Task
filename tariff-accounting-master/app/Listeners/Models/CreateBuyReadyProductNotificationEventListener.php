<?php

namespace App\Listeners\Models;

use App\Assembly;
use App\AssemblyProduct;
use App\BuyNotification;
use App\BuyReadyProductNotification;
use App\Events\Models\CreateBuyReadyProductNotificationEvent;
use App\NotEnoughProduct;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class CreateBuyReadyProductNotificationEventListener
{
    public function __construct()
    {
        //
    }

    public function handle(CreateBuyReadyProductNotificationEvent $event)
    {
        /*
         * Write code here
         */
        if ($model = $event->getModel()){
            $array = [];
            $type = '';

            if (get_class($model) == Assembly::class){
                // TODO:: after append production and production_type check this function
                $array = AssemblyProduct::where('assembly_id',$model->id)->whereRaw('total - (ready + waiting_to_buy) > 0')->whereHas('product',function ($query){
                    $query->where('production',0);
                })->get();
                $type = BuyNotification::ASSEMBLY_TYPE;
            }

            if (count($array) > 0){
                $notify = BuyReadyProductNotification::create([
                    'buy_ready_product_notificationable_id' => $model->id,
                    'buy_ready_product_notificationable_type' => $type,
                    'body'   => "Товарь не хватает на складу",
                    'status' => BuyReadyProductNotification::CREATED
                ]);
                foreach ($array as $item){
                    NotEnoughProduct::create([
                        'buy_ready_product_notification_id' => $notify->id,
                        'product_id'        => $item->product_id,
                        'quantity'           => $item->total - ($item->ready + $item->waiting_to_buy)
                    ]);
                }
            }
        }
    }
}
