<?php

namespace App\Listeners\WriteOff;

use App\Events\Models\CreateRealizationEvent;
use App\Events\WriteOff\WriteOffMaterialWhenSaleCreatedEvent;
use App\WarehouseMaterial;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Sale;

class WriteOffMaterialWhenSaleCreatedListener
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


    public function handle(WriteOffMaterialWhenSaleCreatedEvent $event)
    {
        $items = collect();

        if ($sale = $event->getSale()) {
            $sale_materials = $sale->sale_materials;

            $on_warehouse = 0;

            foreach ($sale_materials as $sale_material) {
                $not_enough_quantity = $sale_material->total - ($sale_material->ready + $sale_material->waiting_to_buy);

                // TODO:: Setting for LIFO and FIFO warehouse materials
                $on_warehouse = $warehouse_materials = WarehouseMaterial::where('material_id', $sale_material->material_id)
                    ->whereRaw('remainder - booked > 0')
                    ->sum(DB::raw('remainder - booked'));

                if ($on_warehouse > 0) {
                    $items->add([
                        'material_id' => $sale_material->material_id,
                        'quantity' => $not_enough_quantity > $on_warehouse ? $on_warehouse : $not_enough_quantity,
                        'issued_from_booked' => 0,
                    ]);
                }
            }

            if (count($items)) {
                $create_realization_event = new CreateRealizationEvent();
                $create_realization_event->setDatetime(date(Controller::ELEMENT_DATE_TIME_FORMAT, strtotime(now())));
                $create_realization_event->setRealizationableId($sale->id);
                $create_realization_event->setRealizationableType(Sale::TABLE_NAME);
                $create_realization_event->setUserId(setting('default_warehouse_user_id', auth()->id()));
                $create_realization_event->setItems($items->all());
                event($create_realization_event);
            }
        }
    }
}
