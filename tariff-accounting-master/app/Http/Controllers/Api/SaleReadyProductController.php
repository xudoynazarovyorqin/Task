<?php

namespace App\Http\Controllers\Api;

use App\Events\Models\SaleReadyProductEvent;
use App\Exceptions\ApiModelNotFoundException;
use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Requests\SaleReadyProductRequest;
use App\Http\Resources\Inventory\SaleReadyProductCollection;
use App\SaleReadyProduct;
use App\SaleReadyProductItem;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SaleReadyProductController extends Controller
{
    protected $response;

    protected $per_page;

    protected $apiResponse;

    private $message_not_found;

    private $saleReadyProduct;

    public function __construct(Response $response, ApiResponse $apiResponse,SaleReadyProduct $saleReadyProduct)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:saleReadyProducts.create')->only('store');
        $this->middleware('permission:saleReadyProducts.show')->only('show');
        $this->middleware('permission:saleReadyProducts.edit')->only('edit');
        $this->middleware('permission:saleReadyProducts.print')->only('print');
        $this->middleware('permission:saleReadyProducts.update')->only('update');
        $this->middleware('permission:saleReadyProducts.delete')->only(['destroy','multipleDelete']);

        $this->response = $response;
        $this->apiResponse = $apiResponse;
        $this->saleReadyProduct = $saleReadyProduct;
        $this->per_page = request()->get('per_page',1000000);
        $this->message_not_found = trans('messages.not_found',['name' => trans('messages.sale_ready_product')]);
    }

    public function index()
    {
        $saleReadyProducts = $this->saleReadyProduct;

        if ($str = \request('search'))
        {
            $saleReadyProducts = $saleReadyProducts->search($str);
        }

        $saleReadyProducts = $saleReadyProducts->filter();
        $saleReadyProducts = $saleReadyProducts->sort()->paginate($this->per_page);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => new \App\Http\Resources\SaleReadyProductCollection($saleReadyProducts)
            ]
        ]);
    }

    public function inventory()
    {
        $saleReadyProducts = $this->saleReadyProduct;

        if ($str = \request('search'))
        {
            $saleReadyProducts = $saleReadyProducts->search($str);
        }

        $saleReadyProducts = $saleReadyProducts->filter();
        $saleReadyProducts = $saleReadyProducts->paginate($this->per_page);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'  =>  new SaleReadyProductCollection($saleReadyProducts)
            ],
        ]);
    }

    public function store(SaleReadyProductRequest $request)
    {
        $event = new SaleReadyProductEvent();
        $event->setDatetime($request->get('datetime',null));
        $event->setClientId($request->get('client_id',null));
        $event->setStatusId($request->get('state_id',null));
        $event->setContractId($request->get('contract_client_id',null));

        if (isset($request['items'])){
            $event->setItems($request['items']);
        }
        event($event);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.store_success',['name' => trans('messages.sale_ready_product')]),
            ]
        ]);
    }

    public function show(SaleReadyProduct $saleReadyProduct)
    {
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'sale'  => new \App\Http\Resources\SaleReadyProduct($saleReadyProduct,true)
            ]
        ]);
    }

    public function update(SaleReadyProductRequest $request, SaleReadyProduct $saleReadyProduct)
    {
        $event = new SaleReadyProductEvent();
        $event->setNew(false);
        $event->setModel($saleReadyProduct);
        $event->setDatetime($request['datetime']);
        $event->setClientId($request['client_id']);
        $event->setStatusId($request['state_id']);
        $event->setContractId($request['contract_client_id']);

        if (isset($request['items'])){
            $event->setItems($request['items']);
        }
        event($event);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.update_success',['name' => trans('messages.sale_ready_product')]),
            ]
        ]);
    }

    public function destroy(SaleReadyProduct $saleReadyProduct)
    {
        try {
            $saleReadyProduct->delete();
        } catch (\Exception $e) {

        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.destroy_success',['name' => trans('messages.sale_ready_product')]),
            ]
        ]);
    }

    public function deleteProduct(){
        if (!$sale_product = SaleReadyProductItem::find(\request()->get('id',null))){
            throw new ApiModelNotFoundException(trans('messages.not_found',['name' => trans('messages.product')]));
        }

        $sale_product->delete();

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.destroy_success',['name' => trans('messages.product')]),
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
                $data  = ChartController::weekly(SaleReadyProduct::class,'total_price'); break;
            case self::MONTHLY:
                $data  = ChartController::monthly(SaleReadyProduct::class,'total_price'); break;
            case self::YEARLY:
                $data  = ChartController::yearly(SaleReadyProduct::class,'total_price'); break;
            case self::PERIOD:
                $data  = ChartController::period(SaleReadyProduct::class, \request('from_date'), \request('to_date'), 'total_price'); break;
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
            SaleReadyProduct::whereIn('id',$request['items'])->each(function($sale){
                $sale->delete();
            });
        }
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.destroy_success',['name' => trans('messages.sale_ready_product')]),
            ]
        ]);
    }

    public function print()
    {
        if (!$saleReadyProduct = $this->saleReadyProduct->find(\request('id'))){
            throw new ApiModelNotFoundException(trans('messages.not_found',['name' => trans('messages.product')]));
        }

        return view('sale_ready_product_invoice',['saleReadyProduct' => $saleReadyProduct]);
    }

    public function getLastId(){
        $ls = SaleReadyProduct::latest('id')->first();
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'last_id' => (($ls ? $ls->id : 0) + 1)
            ]
        ]);
    }
}
