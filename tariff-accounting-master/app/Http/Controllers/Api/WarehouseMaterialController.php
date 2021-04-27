<?php

namespace App\Http\Controllers\Api;

use App\Currency;
use App\Exceptions\ApiModelNotFoundException;
use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Interfaces\WarehouseMaterialInterface;
use App\Http\Resources\WarehouseMaterialCollection;
use App\Http\Resources\WarehouseReportMaterialCollection;
use App\WarehouseMaterial;
use App\Material;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Http\Request;

class WarehouseMaterialController extends Controller implements WarehouseMaterialInterface
{
    protected $response;

    protected $per_page;

    protected $warehouseMaterial;

    protected $apiResponse;

    private $message_not_found;

    public function __construct(Response $response, ApiResponse $apiResponse, WarehouseMaterial $warehouseMaterial)
    {
        $this->middleware('auth:api');

        $this->response = $response;
        $this->warehouseMaterial = $warehouseMaterial;
        $this->apiResponse = $apiResponse;
        $this->per_page = request()->get('per_page',1000000);
        $this->message_not_found = trans('strings.warehouse_material_not_found');
    }

    public function index()
    {
        if ($str = \request('search'))
        {
            $materials = Material::search($str)->filter();
        }
        else {
            $materials = Material::filter();
        }
        $materials = $materials->where(function ($query){
            $ids = WarehouseMaterial::where([
                [ 'remainder', '>', 0]
            ])->pluck('material_id');
            return $query->whereIn('id',$ids);
        })->paginate($this->per_page);

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'    => new WarehouseReportMaterialCollection($materials)
           ]
        ]);
    }


    public function comingMaterials(Request $request)
    {
        if (!$material = Material::find($request['material_id'])){
            throw new ApiModelNotFoundException(trans('messages.not_found',['name' => trans('messages.material')]));
        }

        $comingMaterials = $material->warehouse_materials()->where('remainder','>',0)->paginate($this->per_page);

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'    => [
                   'comingMaterials'  => new WarehouseMaterialCollection($comingMaterials)
               ]
           ]
        ]);
    }


    public function createComing(Request $request)
    {
        if (!$warehouse_materials = $request['warehouse_materials']){
            throw new ApiModelNotFoundException(trans('messages.not_found',['name' => trans('messages.material')]));
        }

        if (!is_array($warehouse_materials)){
            throw new ApiModelNotFoundException(trans('messages.not_found',['name' => trans('messages.material')]));
        }

        foreach ($warehouse_materials as $warehouse_material)
        {
            WarehouseMaterial::create([
               'material_id'         => $warehouse_material['material_id'],
               'warehouse_id'        => $warehouse_material['warehouse_id'],
               'qty_weight'          => $warehouse_material['quantity'],
               'remainder'           => $warehouse_material['quantity'],
               'total_coming'        => $warehouse_material['quantity'],
               'currency_id'         => Currency::MULTI_CURRENCY ? $warehouse_material['currency_id'] : 1,
               'rate'                => Currency::MULTI_CURRENCY ? $warehouse_material['rate'] : 1,
               'buy_price'           => $warehouse_material['buy_price'],
               'price'               => $warehouse_material['price'], // kak selling_price
               'is_reworked'         => 1,
            ]);
        }

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'message' => trans('messages.materials_added_to_warehouse'),
               'data'    => [

               ]
           ]
        ]);
    }

}
