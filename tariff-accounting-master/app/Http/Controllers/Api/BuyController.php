<?php

namespace App\Http\Controllers\Api;

use App\Currency;
use App\Events\Models\BuyEvent;
use App\Events\Models\CreateWarehouseMaterialEvent;
use App\Exceptions\ApiModelNotFoundException;
use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\BuyRequest;
use App\Http\Resources\Relation\BuyMaterialCollection;
use App\Http\Resources\WarehouseMaterialCollection;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Buy;
use App\Http\Resources\BuyCollection;
use App\BuyMaterial;
use App\WarehouseMaterial;

class BuyController extends Controller
{
    protected $response;

    protected $per_page;

    protected $buy;

    protected $apiResponse;

    private $message_not_found;

    public function __construct(Response $response, ApiResponse $apiResponse, Buy $buy)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:buys.create')->only('store');
        $this->middleware('permission:buys.show')->only('show');
        $this->middleware('permission:buys.update')->only('update');
        $this->middleware('permission:buys.delete')->only(['destroy','multipleDelete']);

        $this->response = $response;
        $this->buy = $buy;
        $this->apiResponse = $apiResponse;
        $this->per_page = request()->get('per_page',1000000);;
        $this->message_not_found = trans('messages.not_found',['name' => trans('messages.buy')]);
    }

    public function index()
    {
        $buys = $this->buy;
        if ($str = \request('search'))
        {
            $buys = $buys->search($str);
        }

        $buys = $buys->filter()->sort();

        $buys = $buys
            ->select(
                'buys.id as id','datetime', 'date', 'total_price', 'paid_price', 'comment', 'object_id', 'object_type', 'is_warehouse', 'paid', 'provider_id', 'status_id',
                'contract_provider_id',  'user_id', 'buys.created_at as created_at','buys.updated_at as updated_at',
                DB::raw('SUM(buy_materials.qty_weight) as items_count'),
                DB::raw('SUM(buy_materials.not_enough) as waiting_items_count')
            )
            ->with([
                'provider:id,name,phone,email,actual_address',
                'status:id,state,status',
                'contract_provider:id,number,begin_date,sum,paid',
                'user:id,name',
            ])
            ->join('buy_materials', function ($join) {
                $join->on('buys.id', '=', 'buy_materials.buy_id')
                    ->whereNull('buy_materials.deleted_at');
            })
            ->groupBy('buys.id');

        $buys = $buys->paginate($this->per_page);

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'    => [
                   'buys'          => $buys->items(),
                   'pagination'    => [
                       'total'  => $buys->total(),
                       'current_page'  => $buys->currentPage()
                   ]
               ]
           ]
        ]);
    }

    public function inventory()
    {
        $buys = $this->buy;

        if ($str = \request('search'))
        {
            $buys = $buys->search($str);
        }

        $buys = $buys->filter();
        $buys = $buys->paginate($this->per_page);

        return $this->response->withArray([
           'result' => [
               'success'   => true,
               'data'      => new \App\Http\Resources\Inventory\BuyCollection($buys)
           ],
        ]);
    }

    public function show(Buy $buy)
    {
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'buy'  => new \App\Http\Resources\Buy($buy,true),
                ]
            ]
        ]);
    }

    public function store(BuyRequest $request)
    {
        $event = new BuyEvent();
        $event->setDatetime($request['datetime']);
        $event->setProviderId($request['provider_id']);
        $event->setContractId($request['contract_provider_id']);
        $event->setComment($request['comment']);
        $event->setDate($request['date']);
        $event->setStatusId($request['status_id']);
        $event->setIsWarehouse($request['is_warehouse']);
        $event->setObjectId($request['object_id']);
        $event->setObjectType($request['object_type']);
        $event->setNotificationId($request['buy_notification_id']);

        if ($items = $request->get('items',null)){
            if (is_array($items)){
                $event->setItems($items);
            }
        }
        event($event);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.store_success',['name' => trans('messages.buy')]),
            ]
        ]);

    }

    public function update(BuyRequest $request, Buy $buy)
    {
        $event = new BuyEvent();
        $event->setNew(false);
        $event->setModel($buy);
        $event->setNumber($request['number']);
        $event->setDatetime($request['datetime']);
        $event->setProviderId($request['provider_id']);
        $event->setContractId($request['contract_provider_id']);
        $event->setComment($request['comment']);
        $event->setDate($request['date']);
        $event->setStatusId($request['status_id']);
        $event->setIsWarehouse($request['is_warehouse']);
        $event->setObjectId($request['object_id']);
        $event->setObjectType($request['object_type']);
        $event->setNotificationId($request['buy_notification_id']);

        if ($items = $request['items']){
            if (is_array($items)){
                $event->setItems($items);
            }
        }
        event($event);


        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.update_success',['name' => trans('messages.buy')]),
            ]
        ]);
    }

    public function destroy(Buy $buy)
    {
        try {
            $buy->delete();
        } catch (\Exception $e) {
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.destroy_success',['name' => trans('messages.buy')]),
            ]
        ]);
    }

    public function deleteMaterial(Request $request){
        if (!$buy_material = BuyMaterial::find($request['buy_material_id'])){
            throw new ApiModelNotFoundException(trans('messages.not_found',['name' => trans('messages.material')]));
        }

        if ( !$buy = $buy_material->buy ){
            return $this->response->withArray([
                'result' => [
                    'success' => false
                ],
                'error' => [
                    'code' => ApiResponse::PAGE_NOT_FOUND,
                    'message' => trans('messages.not_found',['name' => trans('messages.buy')])
                ]
            ])->setStatusCode(ApiResponse::PAGE_NOT_FOUND);
        }

        if( count($buy_material->warehouse_materials) )
        {
            return $this->response->withArray([
                'result' => [
                    'success' => false
                ],
                'error' => [
                    'code' => ApiResponse::PAGE_NOT_FOUND,
                    'message'   => trans('messages.already_in_warehouse_material'),
                ]
            ])->setStatusCode(ApiResponse::PAGE_NOT_FOUND);
        }

        $buy_material->delete();

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.destroy_success',['name' => trans('messages.material')]),
            ]
        ]);
    }

    public function receive(Request $request)
    {
        if (!$buy = $this->buy->find($request['buy_id'])){
            throw new ApiModelNotFoundException(trans('messages.not_found',['name' => trans('messages.material')]));
        }

        if (!$warehouse_materials = $request['warehouse_materials']){
            throw new ApiModelNotFoundException($this->message_not_found);
        }

        if (!is_array($warehouse_materials)){
            throw new ApiModelNotFoundException(trans('messages.not_found',['name' => trans('messages.material')]));
        }

        foreach ($warehouse_materials as $warehouse_material)
        {
            $event = new CreateWarehouseMaterialEvent();
            $event->setMaterialId($warehouse_material['material_id']);
            $event->setRemainder($warehouse_material['receive']);
            $event->setPrice($warehouse_material['selling_price']);
            $event->setWarehouseId($warehouse_material['warehouse_id']);
            $event->setWarehouseMaterialableId($warehouse_material['warehouse_materialable_id']);
            $event->setWarehouseMaterialableType(WarehouseMaterial::ABLE_TYPE_BUY_MATERIAL);
            event($event);
        }

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'message' => trans('messages.materials_added_to_warehouse')
           ]
        ]);
    }

    public function chart(){
        if (!$type = \request('type')){
            return response()->json([
                'result'  => [
                    'success' => false,
                ],
                'error' => [
                    'message' => __('messages.not_found',['name' => __('messages.diagram_type')])
                ]
            ]);
        }

        $data = [];
        switch (\request('type')){
            case self::WEEKLY:
                $data  = ChartController::weekly(Buy::class,'total_price'); break;
            case self::MONTHLY:
                $data  = ChartController::monthly(Buy::class,'total_price'); break;
            case self::YEARLY:
                $data  = ChartController::yearly(Buy::class,'total_price'); break;
            case self::PERIOD:
                $data  = ChartController::period(Buy::class, \request('from_date'), \request('to_date'), 'total_price'); break;
        }

        return response()->json([
            'result' => [
                'success' => true,
                'data' => [
                    'chart_data' => $data
                ]
            ],
        ]);
    }

    public function multipleDelete(Request $request){

        if (is_array($request['items'])) {
            Buy::whereIn('id',$request['items'])->each(function($sale){
                $sale->delete();
            });
        }
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.destroy_success',['name' => trans('messages.buy')]),
            ]
        ]);
    }

    public function print(){
        if (!$buy = $this->buy->find(\request()->get('id'))){
            return $this->response->withArray([
                'result' => [
                    'success' => false,
                    'data'    => null
                ],
                'error' => [
                    'message'   => $this->message_not_found,
                    'code'      => ApiResponse::PAGE_NOT_FOUND
                ]
            ])->setStatusCode(ApiResponse::PAGE_NOT_FOUND);
        }

        return view('buy_invoice',['buy' => $buy]);
    }

    public function items(){

        if (!$buy = $this->buy->find(\request()->get('id'))){
            throw new ApiModelNotFoundException($this->message_not_found);
        }

        $items = BuyMaterial::where('buy_id',$buy->id)->paginate($this->per_page);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'items'   => new BuyMaterialCollection($items)
            ]
        ]);
    }

    public function warehouse_materials(){
        if (!$buy = $this->buy->find(\request()->get('id'))){
            throw new ApiModelNotFoundException($this->message_not_found);
        }

        $warehouse_materials = WarehouseMaterial::type(WarehouseMaterial::ABLE_TYPE_BUY_MATERIAL)->whereIn(WarehouseMaterial::ABLE_ID,$buy->buyMaterials->pluck('id'))->paginate($this->per_page);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'warehouse_materials'   => new WarehouseMaterialCollection($warehouse_materials)
            ]
        ]);
    }

    public function getLastId(){
        $ls = Buy::latest('id')->first();
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'last_id' => (($ls ? $ls->id : 0) + 1)
            ]
        ]);
    }
}
