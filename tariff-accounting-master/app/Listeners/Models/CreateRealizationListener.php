<?php

namespace App\Listeners\Models;

use App\Events\Models\CreateRealizationEvent;
use App\Realization;
use App\RealizationMaterial;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateRealizationListener
{
    public function __construct()
    {
        //
    }

    public function handle(CreateRealizationEvent $event)
    {
        $realization = Realization::create([
            'user_id'    => $event->getUserId(),
            'datetime'   => $event->getDatetime(),
            Realization::ABLE_TYPE => $event->getRealizationableType(Realization::ABLE_TYPE),
            Realization::ABLE_ID => $event->getRealizationableId(Realization::ABLE_ID),
        ]);

        foreach ($event->getItems() as $item){
            RealizationMaterial::create([
                'realization_id' => $realization->id,
                'material_id'    => $item['material_id'],
                'quantity'       => $item['quantity'],
                'issued_from_booked' => $item['issued_from_booked']
            ]);
        }
    }
}