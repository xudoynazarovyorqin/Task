<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Material;

use App\{DefectProduct,
    Events\Models\CreateDefectProductEvent,
    Events\Models\CreateSaleEvent,
    Events\Models\CreateWarehouseProductEvent,
    Exceptions\ApiModelNotFoundException,
    Http\Requests\SaleRequest,
    OutputMaterial,
    ProductMaterial,
    Realization,
    RealizationMaterial,
    Sale,
    SaleAdditionalMaterial,
    SaleUser};
use App\Http\Resources\{CommentCollection,
    SaleCollection,
    SaleProductCollection,
    WarehouseProductCollection};
use App\SaleHistory;
use App\SaleMaterial;
use App\SaleMaterialWarehouse;
use App\SaleProduct;
use App\WarehouseMaterial;
use App\WarehouseProduct;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Relation\{Currency, DefectProductCollection as RelationDefectProductCollection};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SaleController extends Controller
{
    protected $response;

    protected $model;

    protected $per_page;

    protected $apiResponse;

    private $message_not_found;

    private $listIndex;

    private $modelIndex;

    public function __construct(Response $response, ApiResponse $apiResponse,Sale $model)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:sales.create')->only('store');
        $this->middleware('permission:sales.show')->only('show');
        $this->middleware('permission:sales.edit')->only('edit');
        $this->middleware('permission:sales.print')->only('print');
        $this->middleware('permission:sales.manufactured')->only('manufacturedStore');
        $this->middleware('permission:sales.defect_product')->only('defectStore');
        $this->middleware('permission:sales.comments')->only(['commentStore','comments']);
        $this->middleware('permission:sales.update')->only('update');
        $this->middleware('permission:sales.delete')->only(['destroy','multipleDelete']);

        $this->response = $response;
        $this->apiResponse = $apiResponse;
        $this->model = $model;
        $this->per_page = request()->get('per_page',1000000);
        $this->message_not_found = trans('messages.not_found',['name' => trans('messages.sale')]);
        $this->listIndex = 'sales';
        $this->modelIndex = 'sale';
    }

    public function index()
    {
        $list = $this->model;

        if ($str = \request('search'))
        {
            $list = $list->search($str);
        }

        $list = $list->filter();
        $list = $list->sort()->paginate($this->per_page);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => new SaleCollection($list)
            ]
        ]);
    }

    public function store(SaleRequest $request)
    {
        $event  = new CreateSaleEvent();

        $event->setRequest([
            'number'     => $request->get('number',null),
            'datetime'   => $request->get('datetime',null),
            'owner'      => Sale::FOR_FIRM,
            'state_id'   => $request->get('state_id',null),
            'priority_id'=> $request->get('priority_id',null),
            'begin_date' => $request->get('begin_date',null),
            'end_date'   => $request->get('end_date',null),
            'level_id'   => $request->get('level_id',null),
            'parent_id'  => $request->get('is_child',false) ? $request['parent_id'] : null,
            'is_child'  => $request->get('is_child',false) ? true : false,
        ]);

        if ($sale_products = $request->get('items',null)) {
            if (is_array($sale_products)) {
                $event->setProducts($sale_products);
            }
        }

       if ($sale_additional_materials = $request->get('additional_materials',null)) {
            if (is_array($sale_additional_materials)) {
                $event->setAdditionalMaterials($sale_additional_materials);
            }
        }

        if( $employees = $request->get('employees',null) )
        {
            if (is_array($employees)){
                $event->setEmployees($employees);
            }
        }

        event($event);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.store_success',['name' => trans('messages.sale')]),
                'data'    => [],
            ]
        ]);
    }

    public function edit($id){

        if (! $model = $this->model->find($id))
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

        $groups = OrderController::getGroups(SaleUser::where('sale_id',$model->id)->with(['user'])->get());

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    $this->modelIndex => $this->responseWithParams($model),
                    'employeeGroups'     => $groups
                ]
            ]
        ]);
    }

    public function show($id)
    {
        return $this->edit($id);
    }

    public function update(SaleRequest $request, Sale $sale)
    {
        $event  = new CreateSaleEvent();

        $event->setRequest([
            'number'     => $request->get('number',null),
            'datetime'   => $request->get('datetime',null),
            'state_id'   => $request->get('state_id',null),
            'priority_id'=> $request->get('priority_id',null),
            'begin_date' => $request->get('begin_date',null),
            'end_date'   => $request->get('end_date',null),
        ]);

        $event->setNew(false);
        $event->setSale($sale);

        if ($sale_products = $request->get('items',null)) {
            if (is_array($sale_products)) {
                $event->setProducts($sale_products);
            }
        }

        if ($sale_additional_materials = $request->get('additional_materials',null)) {
            if (is_array($sale_additional_materials)) {
                $event->setAdditionalMaterials($sale_additional_materials);
            }
        }

        if( $employees = $request->get('employees',null) )
        {
            if (is_array($employees)){
                $event->setEmployees($employees);
            }
        }

        event($event);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.update_success',['name' => trans('messages.sale')]),
            ]
        ]);
    }

    public function destroy(Sale $sale)
    {
        try {
            $sale->delete();
        } catch (\Exception $e) {

        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.destroy_success',['name' => trans('messages.sale')]),
            ]
        ]);
    }

    public function deleteProduct(){

        if (!$sale_product = SaleProduct::find(\request()->get('item_id',null))){
            return $this->response->withArray([
                'result' => [
                    'success' => false
                ],
                'error' => [
                    'code' => ApiResponse::PAGE_NOT_FOUND,
                    'message' => trans('messages.not_found',['name' => trans('messages.product')])
                ]
            ])->setStatusCode(ApiResponse::PAGE_NOT_FOUND);
        }

        $sale_product->delete();

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.destroy_success',['name' => trans('messages.product')]),
            ]
        ]);
    }

    public function historyStore(Request $request){

        if (!$model = $this->model->find($request['sale_id']))
            throw new ApiModelNotFoundException();


        $validator = Validator::make($request->all(),[
            'level_id' => 'required|exists:levels,id',
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

        SaleHistory::create([
            'sale_id'   => $model->id,
            'comment'   => $request['comment'],
            'level_id'  => $request['level_id'],
            'user_id'   => auth()->id()
        ]);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.store_success',['name' => trans('messages.history')]),
                'data'    => [
                    $this->modelIndex => $this->responseWithParams($model)
                ],
            ]
        ]);
    }

    public function print(){

        if (!$model = $this->model->find(\request('id',null))){
            throw new ApiModelNotFoundException();
        }

        return view('sale_invoice',['sale' => $model]);
    }

    public function printSaleMaterials(){

        if (!$model = $this->model->find(\request('sale_id'))){
            throw new ApiModelNotFoundException();
        }

        return view('sale_materials_invoice',['sale' => $model]);
    }

    public function printSaleMaterialWarehouse(){

        if (!$sale = $this->model->find(\request('sale_id'))){
            throw new ApiModelNotFoundException();
        }

        $sale_material_warehouses = SaleMaterialWarehouse::where('sale_id',$sale->id)->get();
        return view('sale_material_warehouse_invoice',compact('sale_material_warehouses','sale'));
    }

    public function report(Request $request){

        $report_materials = [];

        if ($items = $request['products']){
            if (is_array($items)){
                /**
                 * This item ['product_id','quantity']
                 */
                foreach ($items as $item){
                    $product_materials =  ProductMaterial::where('product_id',$item['product_id'])->get();
                    /**
                     * $product_materials ['product_id','material_id','quantity']
                     */
                    foreach ($product_materials as $product_material){
                        if (isset($report_materials[$product_material->material_id])){
                            $report_materials[$product_material->material_id]['total'] += floatval($item['quantity']) * $product_material->quantity;
                        }else{
                            $report_materials[$product_material->material_id]['material'] = new \App\Http\Resources\Material($product_material->material);
                            $report_materials[$product_material->material_id]['total'] = floatval($item['quantity']) * $product_material->quantity;
                        }
                    }
                }
            }
        }

        /**
         * For additional materials
         */
        if ($additional_materials = $request['additional_materials']){
            if (is_array($additional_materials)){
                foreach ($additional_materials as $additional_material){
                    if ($material = Material::find($additional_material['material_id'])){
                        $realQty = floatval($additional_material['quantity']);
                        if ($material->measurement_changeable == true){
                            $rate = $material->additional_measurement_rate > 0 ? $material->additional_measurement_rate : 1;
                            $realQty = $realQty / $rate;
                        }
                        if (isset($report_materials[$material->id])){
                            $report_materials[$material->id]['total'] += $realQty;
                        }else{
                            $report_materials[$material->id]['material'] = new \App\Http\Resources\Material($material);
                            $report_materials[$material->id]['total'] = $realQty ;
                        }
                    }
                }
            }
        }

        $report_materials_with_price = [];

        foreach ($report_materials as $report_material){
            $total_quantity = $report_material['total'];//
            // TODO:: Setting for LIFO and FIFO warehouse materials
            $warehouse_materials = WarehouseMaterial::where('material_id', $report_material['material']['id'])->whereRaw('remainder - booked > 0')->orderBy('created_at',setting('control_materials_sort','asc'))->get();
            foreach ($warehouse_materials as $warehouse_material) {
                $warehouse_remainder = $warehouse_material->remainder - $warehouse_material->booked;
                $used_materials_qty = ($warehouse_remainder >= $total_quantity) ? $total_quantity : $warehouse_remainder;
                array_push($report_materials_with_price,[
                    'material'           => $report_material['material'],
                    'warehouse'          => new \App\Http\Resources\Warehouse($warehouse_material->warehouse),
                    'quantity'           => $used_materials_qty,
                    'price'              => $warehouse_material->buy_price,
                    'status'             => true,
                    'currency'           => new Currency($warehouse_material->currency),
                    'rate'               => floatval($warehouse_material->rate)
                ]);
                $total_quantity -=$used_materials_qty;
                if ($total_quantity == 0)  break;
            }
            if ($total_quantity > 0)
            {
                array_push($report_materials_with_price,[
                    'material'           => $report_material['material'],
                    'quantity'           => $total_quantity,
                    'status'              => false,
                    'message'            => __('messages.material_not_enough_warehouse')
                ]);
            }
        }

        if (\request()->get('for_print') === true){
            return view('sale_report',compact('report_materials_with_price'));
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data' => [
                    'report_materials' => $report_materials_with_price,
                ]
            ]
        ]);
    }

    public function reportShow(){
        /*
        * If report for edit assembly sale_id | order_id
        */
        $output_materials = [];
        $reports = collect();
        $sale = null;
        $sale_id = \request()->get('sale_id',false);
        if ($sale_id == false) {
            if ($order_id = \request()->get('order_id', false))
                $sale = Sale::where('saleable_id', $order_id)->where('saleable_type', 'orders')->first();
        } else
            $sale = Sale::find($sale_id);

        if ($sale){
            $sale_materials = SaleMaterial::where('sale_id',$sale->id)->get();

            $rm_ids = [];
            try {
                $rm_ids = DB::table(RealizationMaterial::TABLE_NAME)
                    ->join(Realization::TABLE_NAME,function ($join) use ($sale){
                        $join->on(Realization::TABLE_NAME . '.id','=',RealizationMaterial::TABLE_NAME.'.realization_id')
                            ->where(Realization::TABLE_NAME. '.' . Realization::ABLE_TYPE,Sale::TABLE_NAME)
                            ->where(Realization::TABLE_NAME. '.' . Realization::ABLE_ID,$sale->id);
                    })
                    ->whereNull(RealizationMaterial::TABLE_NAME .'.deleted_at')
                    ->whereNull(Realization::TABLE_NAME .'.deleted_at')
                    ->pluck(RealizationMaterial::TABLE_NAME.'.id')
                    ->toArray();
            }catch (\Exception $exception){

            }

            $output_materials  = OutputMaterial::whereIn(OutputMaterial::ABLE_ID,$rm_ids)
                ->where(OutputMaterial::ABLE_TYPE,RealizationMaterial::TABLE_NAME)
                ->get();

            $rps = collect();
            foreach ($output_materials as $output_material){
                $rps->push([
                    'material_id'        => $output_material->material_id,
                    'warehouse'          => $output_material->warehouse_material ? new \App\Http\Resources\Relation\Warehouse($output_material->warehouse_material->warehouse) : null,
                    'quantity'           => floatval($output_material->quantity),
                    'price'              => floatval($output_material->warehouse_material ? $output_material->warehouse_material->buy_price : 0),
                    'currency'           => $output_material->warehouse_material ? new Currency($output_material->warehouse_material->currency) : null,
                    'rate'               => floatval($output_material->warehouse_material ? floatval($output_material->warehouse_material->rate) : 1),
                ]);
            }

            foreach ($sale_materials as $sale_material){
                $data = $rps->where('material_id',$sale_material->material_id);
                $total_amount = floatval($data->sum(function ($item){
                    return $item['price'] * $item['rate'];
                }));
                $reports->push([
                    'material'    => new \App\Http\Resources\Relation\Material($sale_material->material),
                    'total'       => floatval($sale_material->total),
                    'shipped'    => $data->sum('quantity'),
                    'price'       => floatval($total_amount / ($data->count() ? $data->count() : 1)),
                    'total_amount' => floatval($total_amount),
                    'data'        => $data->values()->all()
                ]);
            }
        }

        if (\request()->get('for_print') === true){
            return view('sale_show_report',compact('output_materials'));
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data' => [
                    'report_materials' => $reports->values()->all(),
                ]
            ]
        ]);
    }

    public function comments(){

        if (!$model = $this->model->find(\request()->get('sale_id',null))){
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

        $comments = $model->comments;

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'    => [
                'comments' => new CommentCollection($comments),
               ]
           ]
        ]);
    }

    public function commentStore(Request $request){

        if (!$model = $this->model->find($request['model_id'])){
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

        $validator = Validator::make($request->all(), [
            'model_id' => 'required|exists:sales,id',
            'body' => 'required',
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

        $model->comments()->create([
            'body'    => $request['body'],
            'user_id' => auth()->id()
        ]);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.store_success',['name' => __('messages.comment')]),
                'data'    => [],
            ]
        ]);
    }

    private function responseWithParams($model)
    {
        return (new \App\Http\Resources\Sale($model,true));
    }

    public function multipleDelete(Request $request){
        if (is_array($request['items'])) {
            Sale::whereIn('id',$request['items'])->each(function($sale){
                $sale->delete();
            });
        }
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.destroy_success',['name' => trans('messages.sale')]),
            ]
        ]);
    }

    public function backMaterialsToWarehouse(Request $request){

        if (!$sale_material_warehouses = $request['sale_material_warehouses']){
            return $this->response->withArray([
                'result' => [
                    'success' => false,
                    'data'    => []
                ],
                'error' => [
                    'code' => ApiResponse::PAGE_NOT_FOUND,
                    'message' => trans('messages.not_found',['name' => trans('messages.material')]),
                ]
            ])->setStatusCode(ApiResponse::PAGE_NOT_FOUND);
        }

        foreach ($sale_material_warehouses as $sale_material_warehouse){
            if ($saleMaterialWarehouse = SaleMaterialWarehouse::find($sale_material_warehouse['id'])){
                $saleMaterialWarehouse->back = floatval($sale_material_warehouse['back']);
                $saleMaterialWarehouse->update();
            }
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.update_success',['name' => trans('messages.material')]),
            ]
        ]);
    }

    public function deleteAdditionalMaterial(){

        if (!$additional_material = SaleAdditionalMaterial::find(\request()->get('additional_material_id',null))){
            return $this->response->withArray([
                'result' => [
                    'success' => false,
                    'data'    => []
                ],
                'error' => [
                    'code' => ApiResponse::PAGE_NOT_FOUND,
                    'message' => trans('messages.not_found',['name' => trans('messages.material')]),
                ]
            ])->setStatusCode(ApiResponse::PAGE_NOT_FOUND);
        }

        $additional_material->delete();

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.destroy_success',['name' => trans('messages.material')]),
            ],
        ]);
    }

    public function getManufacturedProducts(Request $request){

        if (!$model = $this->model->find(\request()->get('sale_id',null)))
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

        $warehouse_products = $model->warehouse_products();

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'warehouse_products' => new WarehouseProductCollection($warehouse_products),
                ],
            ]
        ]);
    }

    public function getSaleProducts(Request $request){

        if (!$model = $this->model->find(\request()->get('sale_id',null)))
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

        $sale_products = SaleProduct::where('sale_id',$model->id)->get();

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'sale_products' => new SaleProductCollection($sale_products),
                ],
            ]
        ]);
    }

    public function getDefectProducts(Request $request){

        if (!$model = $this->model->find(\request()->get('sale_id',null)))
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

        $defect_products = $model->defect_products()->get();

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'defect_products' => new RelationDefectProductCollection($defect_products),
                ],
            ]
        ]);
    }

    public function manufacturedStore(Request $request){

        $validator = Validator::make($request->all(), [
            "manufactured_products"                 => "required|array|min:1",
            "manufactured_products.*.product_id"    => "required|exists:products,id",
            "manufactured_products.*.warehouse_id"  => "required|exists:warehouses,id",
            "manufactured_products.*.quantity"      => "required|numeric",
            "manufactured_products.*.sale_product_id"      => "required|exists:sale_products,id",
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

        $manufactured_products = \request()->get('manufactured_products',[]);

        if (!$sale_product = SaleProduct::find($manufactured_products[0]['sale_product_id'])){
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

        if (!$sale = $this->model->find($sale_product->sale_id))
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

        foreach (\request()->get('manufactured_products') as $manufactured_product){
            $event = new CreateWarehouseProductEvent();
            $event->setProductId($manufactured_product['product_id']);
            $event->setSellingPrice($manufactured_product['selling_price']);
            $event->setWarehouseProductableId($manufactured_product['sale_product_id']);
            $event->setWarehouseProductableType(WarehouseProduct::WAREHOUSE_ABLE_TYPE_SALE_PRODUCTS);
            $event->setRemainder($manufactured_product['quantity']);
            $event->setReceive($manufactured_product['quantity']);
            $event->setWarehouseId($manufactured_product['warehouse_id']);
            $event->setCurrencyId($manufactured_product['currency_id']);
            $event->setRate($manufactured_product['rate']);

            if ($sale->owner == Sale::FOR_CLIENT){
                $event->setOwner(WarehouseProduct::CLIENT);
                $event->setAgentableType(WarehouseProduct::AGENT_ABLE_TYPE_CLIENTS);
                if ($sale->saleable_type == Sale::SALE_TYPE_ORDERS){
                    $event->setAgentableId(($sale->saleable) ? $sale->saleable->client_id : null);
                }elseif($sale->saleable_type == Sale::SALE_TYPE_ASSEMBLY){
                    $event->setAgentableId(($sale->saleable) ? ($sale->saleable->assemblyable) ? $sale->saleable->assemblyable->client_id : null : null);
                }
            }else{
                $event->setOwner(WarehouseProduct::FIRM);
            }
            event($event);
        }


        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.store_success',['name' => __('messages.product')]),
            ]
        ]);
    }

    public function defectStore(Request $request){

        $validator = Validator::make($request->all(), [
            "defect_products"                 => "required|array|min:1",
            "defect_products.*.product_id"    => "required|exists:products,id",
            "defect_products.*.quantity"      => "required|numeric",
            "defect_products.*.sale_product_id"      => "required|exists:sale_products,id",
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

        if ($defect_products = \request()->get('defect_products')){
            foreach ($defect_products as $defect_product){
                $event = new CreateDefectProductEvent([
                    'defectable_type'     => 'sale_products',
                    'defectable_id'       => $defect_product['sale_product_id'],
                    'product_id'        => $defect_product['product_id'],
                    'quantity'          => $defect_product['quantity'],
                    'date'              => $defect_product['date'],
                ]);
                $event->setReasons($defect_product['reasons']);
                event($event);
            }
        }
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.store_success',['name' => __('messages.product')]),
            ]
        ]);
    }

    public function deleteDefectProduct(){
        if (!$model = DefectProduct::find(\request()->get('id',null))){
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
            ]
        ]);
    }

    public function getLastId(){
        $ls = Sale::latest('id')->first();
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'last_id' => (($ls ? $ls->id : 0) + 1)
            ]
        ]);
    }
}
