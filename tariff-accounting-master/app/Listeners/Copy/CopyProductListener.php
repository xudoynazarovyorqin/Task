<?php

namespace App\Listeners\Copy;

use App\Events\Copy\CopyProductEvent;
use App\Product;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CopyProductListener
{
    public function __construct()
    {
        //
    }

    public function handle(CopyProductEvent $event)
    {
        if ($product  = $event->getProduct()){
            $model = Product::find($product->id);

            $model->load(['materials','categories','semi_products']);

            $newModel = $model->replicate();
            $newModel->name = $model->name . '(копия)';

            $newModel->push();


            foreach($model->getRelations() as $relation => $items){
                foreach($items as $item){
                    unset($item->id);
                    $newModel->{$relation}()->create($item->toArray());
                }
            }
        }
    }
}
