<?php

namespace App\Listeners\WriteOff;

use App\Assembly;
use App\Events\WriteOff\WriteOffMaterialWhenAssemblyCreatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\WarehouseMaterial;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Events\Models\CreateRealizationEvent;

class WriteOffMaterialWhenAssemblyCreatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    public function handle(WriteOffMaterialWhenAssemblyCreatedEvent $event)
    {
        $items = collect();

        if ($assembly = $event->getAssembly()) {
            $assembly_materials = $assembly->assembly_materials;

            $on_warehouse = 0;

            foreach ($assembly_materials as $assembly_material) {
                $not_enough_quantity = $assembly_material->total - ($assembly_material->ready + $assembly_material->waiting_to_buy);

                // TODO:: Setting for LIFO and FIFO warehouse materials
                $on_warehouse = $warehouse_materials = WarehouseMaterial::where('material_id', $assembly_material->material_id)
                    ->whereRaw('remainder - booked > 0')
                    ->sum(DB::raw('remainder - booked'));

                if ($on_warehouse > 0) {
                    $items->add([
                        'material_id' => $assembly_material->material_id,
                        'quantity' => $not_enough_quantity > $on_warehouse ? $on_warehouse : $not_enough_quantity,
                        'issued_from_booked' => 0,
                    ]);
                }
            }

            if (count($items)) {
                $create_realization_event = new CreateRealizationEvent();
                $create_realization_event->setDatetime(date(Controller::ELEMENT_DATE_TIME_FORMAT, strtotime(now())));
                $create_realization_event->setRealizationableId($assembly->id);
                $create_realization_event->setRealizationableType(Assembly::TABLE_NAME);
                $create_realization_event->setUserId(setting('default_warehouse_user_id', auth()->id()));
                $create_realization_event->setItems($items->all());
                event($create_realization_event);
            }
        }
    }
}
