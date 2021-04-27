<?php

namespace App\Http\Controllers\Api;

use App\DefectProduct;
use App\DefectProductReason;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\DefectProductCollection;
use App\Http\Controllers\ApiResponse\ApiResponse;
use EllipseSynergie\ApiResponse\Laravel\Response;

class DefectProductController extends Controller
{
    protected $response;

    protected $model;

    protected $per_page;

    protected $apiResponse;

    private $message_not_found;

    private $listIndex;

    private $modelIndex;

    public function __construct(Response $response, ApiResponse $apiResponse, DefectProduct $model)
    {
        $this->middleware('auth:api');

        $this->response = $response;
        $this->apiResponse = $apiResponse;
        $this->model = $model;
        $this->per_page = request('per_page') ? request('per_page') : 100000000;
        $this->message_not_found = trans('messages.not_found',['name' => __('messages.product')]);
        $this->listIndex  = 'defect_products';
        $this->modelIndex  = 'defect_product';
    }

    public function index()
    {

        $list = $this->model;
        if ($product_id = \request('product_id')){
            $list = $list->whereIn('product_id',Product::search($product_id)->pluck('id')->toArray());
        }

        $list = $list
            ->groupBy('product_id')
            ->selectRaw("product_id, sum(quantity) as quantity")
            ->paginate($this->per_page);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => array(
                    $this->listIndex  =>  new DefectProductCollection($list)
                )
            ]
        ]);
    }

    public function history($product_id){
        $defect_products = DefectProduct::with('defect_product_reasons.reason')->where('product_id',$product_id)->paginate(10000000);

        foreach ($defect_products as $defect_product) {
            switch ( $defect_product->defectable_type ){
                case 'sale_products':
                    $defect_product->defectable_name = 'Заказы производство'; break;
                case 'shipment_products':
                    $defect_product->defectable_name = 'Отгрузка'; break;
                case 'assembly_items':
                    $defect_product->defectable_name = 'Сборка'; break;
            }
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => array(
                    $this->listIndex  => $defect_products
                )
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sale_id' => 'required',
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

        if ($defects = $request['defect_products']) {
            if (is_array($defects)) {
                foreach ($defects as $key => $defect) {
                    $defect_product = DefectProduct::create(
                        [
                            'defectable_id'     => $request['sale_id'],
                            'defectable_type'   => 'sales',
                            'sale_product_id' => $defect['sale_product_id'],
                            'quantity'   => $defect['quantity'],
                            'date'   => $defect['date'],
                        ]
                    );
                    if (isset($defect['reasons'])){
                        if (is_array($defect['reasons'])){
                            foreach ($defect['reasons'] as $reason){
                                DefectProductReason::create([
                                    'defect_product_id' => $defect_product->id,
                                    'reason_id' => $reason['reason_id'],
                                    'quantity'  => $reason['quantity']
                                ]);
                            }
                        }
                    }
                }
            }
        }
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.store_success',['name' => __('messages.product')]),
                'data'    => [
                ],
            ]
        ]);
    }

    public function show($id)
    {
        if (!$model = $this->model->find($id))
        {
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

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    $this->modelIndex  => new \App\Http\Resources\DefectProduct($model)
                ]
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
        if (!$model = $this->model->find($id))
        {
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

        $model->delete();

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.destroy_success',['name' => __('messages.product')]),
                'data'    => [
                    $this->modelIndex => $model
                ],
            ]
        ]);
    }


    public function createDefectFromShipment(Request $request)
    {
        if ( $defects = $request['defect_products'] ){
            if (is_array($defects)){
                foreach ($defects as $defect){
                    if( $defect['quantity'] > 0 )
                    {
                        $defect_product = DefectProduct::create([
                            'defectable_id'     => $defect['shipment_product_id'],
                            'defectable_type'   => 'shipment_products',
                            'product_id'        => $defect['product_id'],
                            'quantity'          => $defect['quantity'],
                            'date'              => $defect['date'],
                        ]);

                        if( $reasons = $defect['reasons'] )
                        {
                            if (is_array($reasons)){
                                foreach ($reasons as $reason){
                                    DefectProductReason::create([
                                        'defect_product_id' => $defect_product->id,
                                        'reason_id'         => $reason['reason_id'],
                                        'quantity'          => $reason['quantity'],
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }


        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.store_success',['name' => __('messages.product')]),
                'data'    => [

                ]
            ]
        ]);
    }

    public function getShipment(Request $request)
    {
        if( $object = \App\Shipment::with('shipment_products.product.measurement', 'shipment_products.defect_products', 'client')->find($request['shipment_id']) )
        {
            foreach ($object->shipment_products as $shipment_product) {
                $shipment_product->sum_defect_products = $shipment_product->defect_products->sum('quantity');
            }

            $shipment_product_ids = $object->shipment_products->pluck('id');
            $old_defect_products = $this->getOldDefectProducts($shipment_product_ids, 'shipment_products');

            return $this->response->withArray([
                'result' => [
                    'success' => true,
                    'data'   => [
                        'object' => $object,
                        'old_defect_products' => $old_defect_products
                    ]
                ]
            ]);
        }
        else
        {
            return $this->response->withArray([
                'result' => [
                    'success' => false
                ],
                'error' => [
                    'code' => ApiResponse::PAGE_NOT_FOUND,
                    'message' => trans('messages.not_found',['name' => trans('messages.object')])
                ]
            ])->setStatusCode(ApiResponse::PAGE_NOT_FOUND);
        }
    }

    public function getOldDefectProducts($defectable_ids = array(), $defectable_type)
    {
        $old_defect_products = DefectProduct::with('defect_product_reasons.reason', 'product.measurement')->where('defectable_type', $defectable_type)->whereIn('defectable_id', $defectable_ids)->get();

        foreach ($old_defect_products as $old_defect_product) {
            $old_defect_product->product_name = ($old_defect_product->product) ? $old_defect_product->product->name : '';
            $old_defect_product->measurement = ($old_defect_product->product && $old_defect_product->product->measurement) ? $old_defect_product->product->measurement->name : '';
        }

        return $old_defect_products;
    }

}
