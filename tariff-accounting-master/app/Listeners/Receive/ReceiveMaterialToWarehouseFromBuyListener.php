<?php

namespace App\Listeners\Receive;

use App\AssemblyMaterial;
use App\Buy;
use App\BuyMaterial;
use App\Events\Receive\ReceiveMaterialToWarehouseFromBuyEvent;
use App\OutputMaterial;
use App\SaleMaterial;
use App\WarehouseMaterial;
use Illuminate\Support\Facades\Log;

class ReceiveMaterialToWarehouseFromBuyListener
{
    public function __construct()
    {
        //
    }

    public function handle(ReceiveMaterialToWarehouseFromBuyEvent $event)
    {
        if ($warehouseMaterial = $event->getWarehouseMaterial()){
            if ($buy_material = BuyMaterial::find($warehouseMaterial->warehouse_materialable_id)){
                $totalReceive = WarehouseMaterial::where([
                    [WarehouseMaterial::ABLE_TYPE, '=',WarehouseMaterial::ABLE_TYPE_BUY_MATERIAL],
                    [WarehouseMaterial::ABLE_ID, '=',$buy_material->id],
                    ['material_id' ,'=', $buy_material->material_id],
                ])->sum('total_coming');
                $buy_material->update([
                    'not_enough' => $buy_material->qty_weight - $totalReceive,
                ]);
            };

            $buy = Buy::find($buy_material->buy_id);

            if( $buy && !$buy->is_warehouse )
            {
                if( $buy->object_type == Buy::OBJECT_TYPE_SALE)
                {
                    $saleMaterial = SaleMaterial::where('sale_id', $buy->object_id)->where('material_id', $warehouseMaterial->material_id)->first();

                    $notEnoughMaterialQuantity = $saleMaterial->total - $saleMaterial->ready;

                    if( $notEnoughMaterialQuantity > 0 )
                    {
                        OutputMaterial::create([
                            OutputMaterial::ABLE_ID     => $buy->object_id,
                            OutputMaterial::ABLE_TYPE   => OutputMaterial::OUTPUT_MATERIAL_TYPE_SALE,
                            'material_id'                => $warehouseMaterial->material_id,
                            'warehouse_material_id'      => $warehouseMaterial->id,
                            'quantity'                   => (($notEnoughMaterialQuantity >= $warehouseMaterial->total_coming) ? $warehouseMaterial->total_coming : $notEnoughMaterialQuantity),
                        ]);
                    }
                }elseif ($buy->object_type == Buy::OBJECT_TYPE_ASSEMBLY){
                    $assemblyMaterial = AssemblyMaterial::where('assembly_id', $buy->object_id)->where('material_id', $warehouseMaterial->material_id)->first();

                    $notEnoughMaterialQuantity = $assemblyMaterial->total - $assemblyMaterial->ready;
                    if( $notEnoughMaterialQuantity > 0 )
                    {
                        OutputMaterial::create([
                            OutputMaterial::ABLE_ID     => $buy->object_id,
                            OutputMaterial::ABLE_TYPE   => OutputMaterial::OUTPUT_MATERIAL_TYPE_ASSEMBLIES,
                            'material_id'                => $warehouseMaterial->material_id,
                            'warehouse_material_id'      => $warehouseMaterial->id,
                            'quantity'                   => ($notEnoughMaterialQuantity >= $warehouseMaterial->total_coming) ? $warehouseMaterial->total_coming : $notEnoughMaterialQuantity
                        ]);
                    }
                }

            }
        }
    }
}
