<?php

namespace App\Listeners\Models;

use App\Events\Models\CreateSaleEvent;
use App\Events\Reservation\ReservationMaterialForSaleEevent;
use App\Events\WriteOff\WriteOffMaterialWhenSaleCreatedEvent;
use App\Sale;
use App\SaleAdditionalMaterial;
use App\SaleProduct;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class CreateSaleEventListener
{
    public function __construct()
    {
        //
    }

    public function handle(CreateSaleEvent $event)
    {
        if ($request = $event->getRequest()){
            /*
             * Create sale
             */
            $sale = $event->getSale();
            if ($event->isNew())
            {
                $sale = Sale::create($request);
            }else{
                $sale->update($request);
            }

            /*
             * Create sale products
             */
            if ($sale_products = $event->getProducts()){
                foreach ($sale_products as $sale_product){
                    SaleProduct::create([
                        'sale_id'       => $sale->id,
                        'product_id'    => $sale_product['product_id'],
                        'quantity'      => $sale_product['quantity'],
                        'order_product_id' => isset($sale_product['order_product_id']) ? $sale_product['order_product_id'] : null
                    ]);
                }
            }

            /*
             * Create additional materials
             */
            if ($additional_materials = $event->getAdditionalMaterials()){
                foreach ($additional_materials as $additional_material){
                    SaleAdditionalMaterial::create([
                        'assembly_id' => $sale->id,
                        'material_id' => $additional_material['material_id'],
                        'quantity'    => $additional_material['quantity'],
                        'sale_id'     => $sale->id
                    ]);
                }
            }

            /*
            * Create sale employees
            */
            if ($employees = $event->getEmployees()) {
                if (is_array($employees) && count($employees) > 0)
                {
                    $sale->users()->detach();
                    $sale->users()->sync($employees);
                }
            }

            if (setting('auto_reservation',false) === true){
                event(new ReservationMaterialForSaleEevent($sale));
            }elseif (setting('automatic_write_off',false) === true){
                event(new WriteOffMaterialWhenSaleCreatedEvent($sale));
            }
        }
    }
}
