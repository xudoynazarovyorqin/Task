<?php

namespace App\Listeners\Models;

use App\Assembly;
use App\AssemblyProduct;
use App\BuyNotification;
use App\Events\Models\CreateSaleEvent;
use App\Events\Models\CreateSaleForAssemblyEvent;
use App\Product;
use App\Sale;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class CreateSaleForAssemblyEventListener
{
    public function __construct()
    {
        //
    }

    public function handle(CreateSaleForAssemblyEvent $event)
    {
        /*
        * Write code here
        */
        if ($model = $event->getAssembly()){
            $array = [];

            if (get_class($model) == Assembly::class){
                // TODO:: after append production and production_type check this function
                $array = AssemblyProduct::where('assembly_id',$model->id)->whereRaw('total - (ready + waiting_to_buy) > 0')->whereHas('product',function ($query){
                    $query->where('production',1)->where('production_type',Product::PRODUCTION);
                })->get();
            }

            if (count($array) > 0){
                $array = $array->map(function ($item) {
                   return [
                        'product_id' => $item['product_id'],
                        'quantity'   => ($item['total'] - ($item['ready'] + $item['waiting_to_buy']))
                   ];
                });

                $create_sale_event = new CreateSaleEvent();

                $create_sale_event->setRequest([
                    'owner'         => Sale::FOR_CLIENT,
                    'is_child'      => false,
                    'reservation_of'=> $model->reservation_of,
                    'saleable_id'   => $model->id,
                    'saleable_type' => BuyNotification::ASSEMBLY_TYPE,
                ]);

                $create_sale_event->setProducts($array);

                // TODO:: run  CreateSaleEvent
                event($create_sale_event);
            }

        }
    }
}
