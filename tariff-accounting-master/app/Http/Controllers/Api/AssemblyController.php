<?php

namespace App\Http\Controllers\Api;

use App\{Assembly,
    AssemblyAdditionalMaterial,
    AssemblyItem,
    AssemblyMaterial,
    AssemblyProduct,
    AssemblyUser,
    DefectProduct,
    Events\Models\CreateDefectProductEvent,
    Events\Models\CreateWarehouseProductEvent,
    Exceptions\ApiModelNotFoundException,
    Http\Requests\AssemblyRequest,
    Material,
    OutputMaterial,
    OutputProduct,
    ProductMaterial,
    ProductSemiProduct,
    Realization,
    RealizationMaterial,
    Sale,
    Shipment,
    ShipmentProduct,
    WarehouseMaterial,
    WarehouseProduct};
use App\Events\Models\AssemblyCreateEvent;
use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Resources\{AssemblyCollection,
    AssemblyItemCollection,
    CommentCollection,
    Warehouse,
    WarehouseProductCollection};
use App\Http\Resources\Relation\{Currency, DefectProductCollection as RelationDefectProductCollection};
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AssemblyController extends Controller
{
    protected $model;

    protected $per_page;

    private $message_not_found;

    private $listIndex;

    private $modelIndex;

    protected $response;

    public function __construct(Response $response, Assembly $model)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:assemblies.create')->only('store');
        $this->middleware('permission:assemblies.show')->only('show');
        $this->middleware('permission:assemblies.edit')->only('edit');
        $this->middleware('permission:assemblies.print')->only('print');
        $this->middleware('permission:assemblies.manufactured')->only('manufacturedStore');
        $this->middleware('permission:assemblies.defect_product')->only('defectStore');
        $this->middleware('permission:assemblies.comments')->only(['commentStore','comments']);
        $this->middleware('permission:assemblies.update')->only('update');
        $this->middleware('permission:assemblies.delete')->only(['destroy','multipleDelete']);

        $this->response = $response;
        $this->model = $model;
        $this->per_page = request()->get('per_page',10000);
        $this->message_not_found = trans('messages.not_found',['name' => trans('messages.assembly')]);
        $this->listIndex = 'assemblies';
        $this->modelIndex = 'assembly';
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
                'data'    => new AssemblyCollection($list)
            ]
        ]);
    }

    public function store(AssemblyRequest $request)
    {
        $event = new AssemblyCreateEvent();

        $event->setRequest([
            'owner'     => Sale::FOR_FIRM,
            'datetime'      => $request->get('datetime',null),
            'begin_date' => $request->get('begin_date',null),
            'end_date'   => $request->get('end_date',null),
            'state_id'   => $request->get('state_id',null),
            'priority_id'=> $request->get('priority_id',null)
        ]);

        $event->setAdditionalMaterials($request->get('additional_materials',null));
        $event->setItems($request->get('items',null));
        $event->setEmployees($request->get('employees',null));
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
        if (!$model = $this->model->find($id))
            throw new ApiModelNotFoundException();

        $groups = OrderController::getGroups(AssemblyUser::where('assembly_id',$model->id)->with(['user'])->get());

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'assembly' => new \App\Http\Resources\Assembly($model,true),
                    'employeeGroups'     => $groups
                ],
            ]
        ]);
    }

    public function show($id)
    {
        return $this->edit($id);
    }

    public function update(AssemblyRequest $request, $id)
    {
        if (!$model = $this->model->find($id))
            throw new ApiModelNotFoundException();

        $event = new AssemblyCreateEvent();

        $event->setAssembly($model);

        $event->setNew(false);

        $event->setRequest([
            'datetime'      => $request->get('datetime',null),
            'begin_date' => $request->get('begin_date',null),
            'end_date'   => $request->get('end_date',null),
            'state_id'   => $request->get('state_id',null),
            'priority_id'=> $request->get('priority_id',null)
        ]);

        $event->setAdditionalMaterials($request->get('additional_materials',null));
        $event->setItems($request->get('items',null));
        $event->setEmployees($request->get('employees',null));
        event($event);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.store_success',['name' => trans('messages.sale')]),
                'data'    => [],
            ]
        ]);
    }

    public function destroy($id)
    {
        if (!$model = $this->model->find($id))
            throw new ApiModelNotFoundException();

        $model->delete();

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.destroy_success',['name' => trans('messages.sale')]),
            ]
        ]);
    }

    public function multipleDelete(Request $request){
        if (is_array($request['items'])) {
            Assembly::whereIn('id',$request['items'])->each(function($sale){
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

    public function deleteProduct(){
        if (!$assembly_item = AssemblyItem::find(\request()->get('assembly_item_id',null)))
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
        };

        $assembly_item->delete();

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.destroy_success',['name' => __('messages.product')]),
                'data'    => []
            ]
        ]);
    }

    public function deleteAdditionalMaterial(){
        if (!$additional_material = AssemblyAdditionalMaterial::find(\request()->get('additional_material_id',null)))
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
        };

        $additional_material->delete();

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.destroy_success',['name' => __('messages.material')]),
                'data'    => []
            ]
        ]);
    }

    public function report(Request $request){

        $report_materials = [];
        $report_products = [];

        $report_products_with_price = [];
        $report_materials_with_price = [];

        if ($items = $request['products']){
            if (is_array($items)){
                /**
                 * This item ['product_id','quantity']
                 */
                foreach ($items as $item){
                   $item_products  =  ProductSemiProduct::where('product_id',$item['product_id'])->get();
                   $item_materials =  ProductMaterial::where('product_id',$item['product_id'])->get();

                    /**
                     * $item_product ['product_id','semi_product_id','quantity']
                     */
                       foreach ($item_products as $item_product){
                            if (isset($report_products[$item_product->semi_product_id])){
                                $report_products[$item_product->semi_product_id]['total'] += floatval($item['quantity']) * $item_product->quantity;
                            }else{
                                $report_products[$item_product->semi_product_id]['total'] = floatval($item['quantity']) * $item_product->quantity;
                                $report_products[$item_product->semi_product_id]['product'] = new \App\Http\Resources\Product($item_product->semi_product);
                            }
                       }

                       /**
                        * $item_material ['product_id','material_id','quantity']
                        */
                       foreach ($item_materials as $item_material){
                           if (isset($report_materials[$item_material->material_id])){
                               $report_materials[$item_material->material_id]['total'] += floatval($item['quantity']) * $item_material->quantity;
                           }else{
                               $report_materials[$item_material->material_id]['material'] = new \App\Http\Resources\Material($item_material->material);
                               $report_materials[$item_material->material_id]['total'] = floatval($item['quantity']) * $item_material->quantity;
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

        foreach ($report_materials as $report_material){
            $total_quantity = $report_material['total'];
            // TODO:: Setting for LIFO and FIFO warehouse materials
            $warehouse_materials = WarehouseMaterial::where('material_id', $report_material['material']['id'])->where('remainder','>',0)->orderBy('created_at',setting('control_materials_sort','asc'))->get();
            foreach ($warehouse_materials as $warehouse_material) {
                $used_materials_qty = ($warehouse_material->remainder >= $total_quantity) ? $total_quantity : $warehouse_material->remainder;
                array_push($report_materials_with_price,[
                    'material'           => $report_material['material'],
                    'warehouse'          => new Warehouse($warehouse_material->warehouse),
                    'quantity'           => $used_materials_qty,
                    'remainder'          => $warehouse_material->remainder,
                    'price'              => $warehouse_material->buy_price,
                    'currency'           => new Currency($warehouse_material->currency),
                    'rate'               => floatval($warehouse_material->rate),
                    'status'             => true,
                ]);
                $total_quantity -=$used_materials_qty;
                if ($total_quantity == 0)
                    break;
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

        foreach ($report_products as $report_product){
            $total_quantity = $report_product['total'];
            // TODO:: Setting for LIFO and FIFO warehouse products
            $warehouse_products = WarehouseProduct::where('product_id', $report_product['product']['id'])->where('remainder','>',0)->orderBy('created_at',setting('control_materials_sort','asc'))->get();
            foreach ($warehouse_products as $warehouse_product) {
                $used_qty = ($warehouse_product->remainder >= $total_quantity) ? $total_quantity : $warehouse_product->remainder;
                array_push($report_products_with_price,[
                    'product'           => $report_product['product'],
                    'warehouse'          => new Warehouse($warehouse_product->warehouse),
                    'quantity'           => $used_qty,
                    'remainder'          => $warehouse_product->remainder,
                    'price'              => $warehouse_product->buy_price,
                    'currency'           => new Currency($warehouse_product->currency),
                    'rate'               => floatval($warehouse_product->rate),
                    'status'             => true
                ]);
                $total_quantity -=$used_qty;
                if ($total_quantity == 0)
                    break;
            }
            if ($total_quantity > 0)
            {
                array_push($report_products_with_price,[
                    'product'               => $report_product['product'],
                    'quantity'              => $total_quantity,
                    'status'                => false,
                    'message'               => __('messages.product_not_enough_warehouse')
                ]);
            }
        }

        if (\request()->get('for_print') === true){
            return view('assembly_report',compact('report_materials_with_price','report_products_with_price'));
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data' => [
                    'report_products' => $report_products_with_price,
                    'report_materials' => $report_materials_with_price,
                ]
            ]
        ]);
    }

    public function reportShow(){
        /*
        * If report for edit assembly assembly_id | order_id
        */
        $output_materials = [];
        $output_products = [];

        $report_materials = collect();
        $report_products = collect();

        $assembly = null;
        $assembly_id = \request()->get('assembly_id',false);
        if ($assembly_id == false){
            if ($order_id = \request()->get('order_id',false))
                $assembly = Assembly::where('assemblyable_id',$order_id)->where('assemblyable_type','orders')->first();
        }
        else
            $assembly = Assembly::find($assembly_id);

        if ($assembly){
            /**
             * Configure assembly materials
             */
            $assembly_materials = AssemblyMaterial::where('assembly_id',$assembly->id)->get();

            $rm_ids = [];
            try {
                $rm_ids = DB::table(RealizationMaterial::TABLE_NAME)
                    ->join(Realization::TABLE_NAME,function ($join){
                        $join->on(Realization::TABLE_NAME . '.id','=',RealizationMaterial::TABLE_NAME.'.realization_id');
                    })
                    ->whereNull(RealizationMaterial::TABLE_NAME .'.deleted_at')
                    ->whereNull(Realization::TABLE_NAME .'.deleted_at')
                    ->where(Realization::TABLE_NAME. '.' . Realization::ABLE_TYPE,Assembly::TABLE_NAME)
                    ->where(Realization::TABLE_NAME. '.' . Realization::ABLE_ID,$assembly->id)
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

            foreach ($assembly_materials as $assembly_material){
                $data = $rps->where('material_id',$assembly_material->material_id);
                $total_amount = floatval($data->sum(function ($item){
                    return $item['price'] * $item['rate'];
                }));
                $report_materials->push([
                    'material'    => new \App\Http\Resources\Relation\Material($assembly_material->material),
                    'total'       => floatval($assembly_material->total),
                    'shipped'    => $data->sum('quantity'),
                    'price'       => floatval($total_amount / ($data->count() ? $data->count() : 1)),
                    'total_amount' => floatval($total_amount),
                    'data'        => $data->values()->all()
                ]);
            }

            /***
             * Configure assembly products
             */
            $assembly_products = AssemblyProduct::where('assembly_id',$assembly->id)->get();

            $shp_ids = [];
            try {
                $shp_ids = DB::table(ShipmentProduct::TABLE_NAME)
                    ->join(Shipment::TABLE_NAME,function ($join){
                        $join->on(Shipment::TABLE_NAME . '.id','=',ShipmentProduct::TABLE_NAME.'.shipment_id');
                    })
                    ->whereNull(ShipmentProduct::TABLE_NAME .'.deleted_at')
                    ->whereNull(Shipment::TABLE_NAME .'.deleted_at')
                    ->where(Shipment::TABLE_NAME . '.' . Shipment::ABLE_TYPE,'=',Assembly::TABLE_NAME)
                    ->where(Shipment::TABLE_NAME. '.' . Shipment::ABLE_ID,$assembly->id)
                    ->pluck(ShipmentProduct::TABLE_NAME.'.id')
                    ->toArray();
            }catch (\Exception $exception){

            }
            $output_products  = OutputProduct::whereIn(OutputProduct::ABLE_ID,$shp_ids)
                ->where(OutputProduct::ABLE_TYPE,ShipmentProduct::TABLE_NAME)
                ->get();

            $rps = collect();
            foreach ($output_products as $output_product){
                $rps->push([
                    'product_id'        => $output_product->product_id,
                    'warehouse'          => $output_product->warehouse_product ? new \App\Http\Resources\Relation\Warehouse($output_product->warehouse_product->warehouse) : null,
                    'quantity'           => floatval($output_product->quantity),
                    'price'              => floatval($output_product->warehouse_product ? $output_product->warehouse_product->buy_price : 0),
                    'currency'           => $output_product->warehouse_product ? new Currency($output_product->warehouse_product->currency) : null,
                    'rate'               => floatval($output_product->warehouse_product ? floatval($output_product->warehouse_product->rate) : 1),
                ]);
            }
            foreach ($assembly_products as $assembly_product){
                $data = $rps->where('product_id',$assembly_product->product_id);
                $total_amount = floatval($data->sum(function ($item){
                    return $item['price'] * $item['rate'];
                }));
                $report_products->push([
                    'product'    => new \App\Http\Resources\Relation\Product($assembly_product->product),
                    'total'       => floatval($assembly_product->total),
                    'shipped'    => $data->sum('quantity'),
                    'price'       => floatval($total_amount / ($data->count() ? $data->count() : 1)),
                    'total_amount' => floatval($total_amount),
                    'data'        => $data->values()->all()
                ]);
            }
        }

        if (\request()->get('for_print') === true){
            return view('assembly_show_report',compact('output_materials','output_products'));
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data' => [
                    'report_materials' => $report_materials->values()->all(),
                    'report_products'  => $report_products->values()->all(),
                ]
            ]
        ]);
    }

    public function getAssemblyItems(Request $request){

        if (!$model = $this->model->find(\request()->get('assembly_id',null)))
            throw new ApiModelNotFoundException();

        $assembly_items  = AssemblyItem::where('assembly_id',$model->id)->get();

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'assembly_items'  => new AssemblyItemCollection($assembly_items)
                ],
            ]
        ]);
    }

    public function getManufacturedProducts(Request $request){

        if (!$model = $this->model->find(\request()->get('assembly_id',null)))
            throw new ApiModelNotFoundException();

        $warehouse_products = $model->warehouse_products()->get();

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'warehouse_products' => new WarehouseProductCollection($warehouse_products),
                ],
            ]
        ]);
    }

    public function getDefectProducts(Request $request){

        if (!$model = $this->model->find(\request()->get('assembly_id',null)))
            throw new ApiModelNotFoundException();

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

    public function print(){
        if (!$model = $this->model->find(\request()->get('id',null)))
            throw new ApiModelNotFoundException();

        return view('assembly_invoice',['assembly' => $model]);
    }

    public function comments(){

        if (!$model = $this->model->find(\request()->get('assembly_id',null)))
            throw new ApiModelNotFoundException();

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

        if (!$model = $this->model->find(\request()->get('model_id',null)))
            throw new ApiModelNotFoundException();

        $validator = Validator::make($request->all(), [
            'model_id' => 'required|exists:assemblies,id',
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
            ]
        ]);
    }

    public function manufacturedStore(Request $request){

        $validator = Validator::make($request->all(), [
            "manufactured_products"                 => "required|array|min:1",
            "manufactured_products.*.product_id"    => "required|exists:products,id",
            "manufactured_products.*.warehouse_id"  => "required|exists:warehouses,id",
            "manufactured_products.*.quantity"      => "required|numeric",
            "manufactured_products.*.assembly_item_id"      => "required|exists:assembly_items,id",
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

        if (!$assembly_item = AssemblyItem::find($manufactured_products[0]['assembly_item_id'])){
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

        if (!$assembly = $this->model->find($assembly_item->assembly_id))
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
            $event->setWarehouseProductableId($manufactured_product['assembly_item_id']);
            $event->setWarehouseProductableType(WarehouseProduct::WAREHOUSE_ABLE_TYPE_ASSEMBLY_ITEM);
            $event->setRemainder($manufactured_product['quantity']);
            $event->setReceive($manufactured_product['quantity']);
            $event->setWarehouseId($manufactured_product['warehouse_id']);
            $event->setCurrencyId($manufactured_product['currency_id']);
            $event->setRate($manufactured_product['rate']);

            if ($assembly->owner == Sale::FOR_CLIENT){
                $event->setOwner(WarehouseProduct::CLIENT);
                $event->setAgentableType(WarehouseProduct::AGENT_ABLE_TYPE_CLIENTS);
                if ($assembly->assemblyable_type == Assembly::SALE_TYPE_ORDERS){
                    $event->setAgentableId(($assembly->assemblyable) ? $assembly->assemblyable->client_id : null);
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

    public function defectStore(){
        if ($defect_products = \request()->get('defect_products')){
            foreach ($defect_products as $defect_product){
                $event = new CreateDefectProductEvent([
                    'defectable_type' => 'assembly_items',
                    'defectable_id'   => $defect_product['assembly_item_id'],
                    'product_id'      => $defect_product['product_id'],
                    'quantity'        => $defect_product['quantity'],
                    'date'            => $defect_product['date'],
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
        $ls = Assembly::latest('id')->first();
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'last_id' => (($ls ? $ls->id : 0) + 1)
            ]
        ]);
    }
}
