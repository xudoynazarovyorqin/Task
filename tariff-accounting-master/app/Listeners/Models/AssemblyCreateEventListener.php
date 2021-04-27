<?php

namespace App\Listeners\Models;

use App\Assembly;
use App\AssemblyAdditionalMaterial;
use App\AssemblyItem;
use App\Events\Models\AssemblyCreateEvent;
use App\Events\Reservation\ReservationMaterialForAssemblyEevent;
use App\Events\Reservation\ReservationProductForAssemblyEvent;
use App\Events\WriteOff\WriteOffMaterialWhenAssemblyCreatedEvent;
use App\Events\WriteOff\WriteOffMaterialWhenSaleCreatedEvent;
use App\Events\WriteOff\WriteOffProductWhenAssemblyCreatedEvent;
use Illuminate\Support\Facades\Log;

class AssemblyCreateEventListener
{
    public function __construct()
    {
        //
    }

    public function handle(AssemblyCreateEvent $event)
    {
        if ($request = $event->getRequest()) {
            /*
             * Create new assembly
             */

            $model = $event->getAssembly();

            if ($event->isNew()){
                $model = Assembly::create($request);
            }else{
                $model->update($request);
            }

            /*
             * Create assembly items
             */
            if ($items = $event->getItems()) {
                foreach ($items as $item) {
                    AssemblyItem::create([
                        'assembly_id' => $model->id,
                        'product_id'  => $item['product_id'],
                        'quantity'    => $item['quantity'],
                        'ready'       => 0,
                        'order_product_id' => isset($item['order_product_id']) ? $item['order_product_id'] : null
                    ]);
                }
            }

            /*
             * Create assembly employees
             */
            if ($employees = $event->getEmployees()) {
                if (is_array($employees) && count($employees) > 0)
                {
                    $model->users()->detach();
                    $model->users()->sync($employees);
                }
            }

            /**
             * Create assembly additional materials
             */
            if ($additional_materials = $event->getAdditionalMaterials()){
                foreach ($additional_materials as $additional_material){
                    AssemblyAdditionalMaterial::create([
                        'assembly_id' => $model->id,
                        'material_id' => $additional_material['material_id'],
                        'quantity'    => $additional_material['quantity'],
                    ]);
                }
            }

            /**
             * Reservation material for assembly
             */

            if (setting('auto_reservation',false) === true){
                event(new ReservationMaterialForAssemblyEevent($model));
            }elseif (setting('automatic_write_off',false) === true){
                 event(new WriteOffProductWhenAssemblyCreatedEvent($model));
            }

            /**
             * Reservation products for assembly
             */
            if (setting('auto_reservation',false) === true){
                event(new ReservationProductForAssemblyEvent($model));
            }elseif (setting('automatic_write_off',false) === true){
                    event(new WriteOffMaterialWhenAssemblyCreatedEvent($model));
            }
        }
    }
}