<?php

namespace App\Http\Controllers\Api;

use App\Currency;
use App\Events\Models\BuyReadyProductEvent;
use App\Events\Models\CreateWarehouseProductEvent;
use App\Exceptions\ApiModelNotFoundException;
use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\BuyReadyProductRequest;
use App\Http\Resources\Relation\BuyProductCollection;
use App\Http\Resources\WarehouseProductCollection;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\BuyReadyProductCollection;
use App\BuyReadyProduct;
use App\BuyReadyProductList;
use App\WarehouseProduct;

class BuyReadyProductController extends Controller
{
    protected $response;

    protected $per_page;

    protected $buy;

    protected $apiResponse;

    private $message_not_found;

    public function __construct(Response $response, ApiResponse $apiResponse, BuyReadyProduct $buy)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:buyReadyProducts.create')->only('store');
        $this->middleware('permission:buyReadyProducts.show')->only('show');
        $this->middleware('permission:buyReadyProducts.update')->only(['update','deleteProduct']);
        $this->middleware('permission:buyReadyProducts.delete')->only(['destroy','multipleDelete']);

        $this->response = $response;
        $this->buy = $buy;
        $this->apiResponse = $apiResponse;
        $this->per_page = request()->get('per_page',1000000);
        $this->message_not_found = trans('messages.not_found',['name' => trans('messages.buy_ready_product')]);
    }

    public function index()
    {
        $buys = $this->buy;
        if ($str = \request('search'))
        {
            $buys = $buys->search($str);
        }

        $buys = $buys->filter();
        $buys = $buys->sort()->paginate($this->per_page);

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'    => new BuyReadyProductCollection($buys)
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
                'success' => true,
                'data'  => new \App\Http\Resources\Inventory\BuyReadyProductCollection($buys)
            ],
        ]);
    }

    public function show(BuyReadyProduct $buyReadyProduct)
    {
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'buy'  => new \App\Http\Resources\BuyReadyProduct($buyReadyProduct,true)
                ]
            ]
        ]);
    }

    public function store(BuyReadyProductRequest $request)
    {
        $event = new BuyReadyProductEvent();
        $event->setDatetime($request->get('datetime',now()));
        $event->setProviderId($request->get('provider_id',null));
        $event->setContractId($request->get('contract_provider_id',null));
        $event->setComment($request->get('comment',''));
        $event->setDate($request->get('date',null));
        $event->setStatusId($request->get('status_id',null));
        $event->setIsWarehouse($request->get('is_warehouse',null));
        $event->setObjectId($request->get('object_id',null));
        $event->setObjectType($request->get('object_type',null));
        $event->setNotificationId($request->get('buy_notification_id',null));

        if ($items = $request->get('items',null)){
            if (is_array($items)){
                $event->setItems($items);
            }
        }

        event($event);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.store_success',['name' => trans('messages.buy_ready_product')])
            ]
        ]);
    }

    public function update(BuyReadyProductRequest $request, BuyReadyProduct $buyReadyProduct)
    {
        $event = new BuyReadyProductEvent();
        $event->setNew(false);
        $event->setModel($buyReadyProduct);
        $event->setDatetime($request->get('datetime',now()));
        $event->setProviderId($request->get('provider_id',null));
        $event->setContractId($request->get('contract_provider_id',null));
        $event->setComment($request->get('comment',''));
        $event->setDate($request->get('date',null));
        $event->setStatusId($request->get('status_id',null));
        $event->setIsWarehouse($request->get('is_warehouse',null));
        $event->setObjectId($request->get('object_id',null));
        $event->setObjectType($request->get('object_type',null));
        $event->setNotificationId($request->get('buy_notification_id',null));

        if ($items = $request->get('items',null)){
            if (is_array($items)){
                $event->setItems($items);
            }
        }

        event($event);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.update_success',['name' => trans('messages.buy_ready_product')]),
            ]
        ]);
    }

    public function destroy(BuyReadyProduct $buyReadyProduct)
    {

        try {
            $buyReadyProduct->delete();
        } catch (\Exception $e) {

        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.destroy_success',['name' => trans('messages.buy_ready_product')]),
            ]
        ]);
    }

    public function deleteProduct(Request $request){
        if (!$buy_product = BuyReadyProductList::find($request->get('buy_product_id',null))){
            throw new ApiModelNotFoundException(trans('messages.not_found',['name' => trans('messages.product')]));
        }

        if( count($buy_product->warehouse_products) )
        {
            throw new ApiModelNotFoundException(trans('messages.already_in_warehouse_product'));
        }

        $buy_product->delete();

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.destroy_success',['name' => trans('messages.product')]),
            ]
        ]);
    }

    public function receive(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "warehouse_products"                 => "required|array|min:1",
            "warehouse_products.*.product_id"    => "required|exists:products,id",
            "warehouse_products.*.warehouse_id"  => "required|exists:warehouses,id",
            "warehouse_products.*.receive"       => "required|numeric",
        ]);

        if ($validator->fails()) {
            return $this->response->withArray(
                [
                    'result' => [
                        'success' => false,
                        'data'     => []
                    ],
                    'error' => [
                        'message' => __('messages.error'),
                        'code'    => ApiResponse::VALIDATION_ERROR
                    ],
                    'validation_errors' => $validator->errors()
                ])->setStatusCode(ApiResponse::VALIDATION_ERROR);
        }

        if (!$buy = $this->buy->find($request['buy_id'])){
            throw new ApiModelNotFoundException($this->message_not_found);
        }

        if (!$warehouse_products = $request['warehouse_products']){
            throw new ApiModelNotFoundException(trans('messages.not_found',['name' => trans('messages.product')]));
        }

        if (!is_array($warehouse_products)){
            throw new ApiModelNotFoundException(trans('messages.not_found',['name' => trans('messages.product')]));
        }

        foreach ($warehouse_products as $buy_product)
        {
            if ($buy_product['receive'] > 0){
                $event = new CreateWarehouseProductEvent();
                $event->setProductId($buy_product['product_id']);
                $event->setSellingPrice($buy_product['selling_price']);
                $event->setWarehouseProductableId($buy_product['warehouse_productable_id']);
                $event->setWarehouseProductableType(WarehouseProduct::WAREHOUSE_ABLE_TYPE_BUY_READY_PRODUCT_LISTS);
                $event->setRemainder($buy_product['receive']);
                $event->setReceive($buy_product['receive']);
                $event->setWarehouseId($buy_product['warehouse_id']);
                $event->setOwner(WarehouseProduct::PROVIDER);
                $event->setAgentableType(WarehouseProduct::AGENT_ABLE_TYPE_PROVIDERS);
                $event->setAgentableId($buy->provider_id);
                event($event);
            }
        }

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'message' => trans('messages.products_added_to_warehouse')
           ]
        ]);
    }

    public function multipleDelete(Request $request){

        if (is_array($request->get('items',null))) {
            BuyReadyProduct::whereIn('id',$request['items'])->each(function($sale){
                $sale->delete();
            });
        }
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.destroy_success',['name' => trans('messages.buy_ready_product')]),
            ]
        ]);
    }

    public function print(){

        if (!$buy = $this->buy->find(\request()->get('id',null))){
            throw new ApiModelNotFoundException(trans($this->message_not_found));
        }

        return view('buy_ready_product_invoice',['buy' => $buy]);
    }

    public function chart(){
        if (!$type = \request('type',null)){
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
                $data  = ChartController::weekly(BuyReadyProduct::class,'total_price'); break;
            case self::MONTHLY:
                $data  = ChartController::monthly(BuyReadyProduct::class,'total_price'); break;
            case self::YEARLY:
                $data  = ChartController::yearly(BuyReadyProduct::class,'total_price'); break;
            case self::PERIOD:
                $data  = ChartController::period(BuyReadyProduct::class, \request('from_date'), \request('to_date'), 'total_price'); break;
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

    public function items(){

        if (!$buy = $this->buy->find(\request()->get('id',null))){
            throw new ApiModelNotFoundException($this->message_not_found);
        }

        $items = BuyReadyProductList::where('buy_id',$buy->id)->paginate($this->per_page);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'items'   => new BuyProductCollection($items)
            ]
        ]);
    }

    public function warehouse_products(){

        if (!$buy = $this->buy->find(\request()->get('id'))){
            throw new ApiModelNotFoundException($this->message_not_found);
        }

        $warehouse_products = WarehouseProduct::type(WarehouseProduct::WAREHOUSE_ABLE_TYPE_BUY_READY_PRODUCT_LISTS)->whereIn(WarehouseProduct::ABLE_ID,$buy->buyProducts->pluck('id'))->get();

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'warehouse_products'   => new WarehouseProductCollection($warehouse_products)
            ]
        ]);
    }

    public function getLastId(){
        $ls = BuyReadyProduct::latest('id')->first();
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'last_id' => (($ls ? $ls->id : 0) + 1)
            ]
        ]);
    }
}
