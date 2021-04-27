<?php

namespace App\Http\Controllers\Api;

use App\Assembly;
use App\AssemblyAdditionalMaterial;
use App\AssemblyUser;
use App\EmployeeGroup;
use App\Events\Models\CreateOrderEvent;
use App\Events\Update\UpdateOrderEvent;
use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\CommentCollection;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\Relation\AssemblyAdditionalMaterialCollection;
use App\Http\Resources\Relation\SaleAdditionalMaterialCollection;
use App\Order;
use App\OrderCost;
use App\OrderProduct;
use App\Product;
use App\Sale;
use App\SaleAdditionalMaterial;
use App\SaleUser;
use Carbon\Carbon;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{

    protected $response;

    protected $per_page;

    protected $material;

    protected $apiResponse;

    private $message_not_found;

    private $listIndex;

    private $model;

    public function __construct(Response $response, ApiResponse $apiResponse, Order $order)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:orders.create')->only('store');
        $this->middleware('permission:orders.show')->only('show');
        $this->middleware('permission:orders.edit')->only('edit');
        $this->middleware('permission:orders.print')->only('print');
        $this->middleware('permission:orders.update')->only('update');
        $this->middleware('permission:orders.comments')->only(['commentStore','comments']);
        $this->middleware('permission:orders.delete')->only(['destroy','multipleDelete']);

        $this->response = $response;
        $this->model = $order;
        $this->apiResponse = $apiResponse;
        $this->per_page = request()->get('per_page',1000000) ;
        $this->message_not_found = trans('messages.not_found',['name' => trans('messages.order')]);
        $this->listIndex = 'orders';
    }

    public static function getGroups($users)
    {
        $groups = [];
        if (count($users) > 0)
        {
            $employeeGroups = EmployeeGroup::all();
            $grouped = $users->groupBy('employee_group_id')->toArray();

            foreach ($grouped as $key => $group){
                if ($e_g = $employeeGroups->firstWhere('id',$key))
                {
                    $e_g['users'] = $group;
                    array_push($groups,$e_g);
                }
            }
        }
        return $groups;
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
                'data'    => new OrderCollection($list)
            ]
        ]);
    }

    public function inventory()
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
                'data'    => new \App\Http\Resources\Inventory\OrderCollection($list)
            ]
        ]);
    }

    public function store(OrderRequest $request)
    {
        $event = new CreateOrderEvent();

        $event->setNumber($request['number']);
        $event->setDatetime($request['datetime']);
        $event->setProductionType($request['production_type']);
        $event->setPriorityId($request['priority_id']);
        $event->setStateId($request['state_id']);
        $event->setClientId($request['client_id']);
        $event->setBeginDate($request['begin_date']);
        $event->setEndDate($request['end_date']);
        $event->setContractClientId($request['contract_client_id']);

        if ($products = $request['order_products']){
            $event->setProducts($products);
        }

        if ($additional_materials = $request['additional_materials']){
            $event->setAdditionalMaterials($additional_materials);
        }

        if ($costs = $request['order_costs']){
            $event->setCosts($costs);
        }

        if ($employees = $request['employees']){
            $event->setEmployees($employees);
        }

        event($event);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.store_success',['name' => __('messages.order')]),
            ]
        ]);
    }

    public function show($id)
    {
        return $this->edit($id);
    }

    public function edit($id){

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

        $additional_materials = [];
        $users = [];

        if ($model->production_type == Product::PRODUCTION){
            $sale = Sale::where('saleable_type','orders')->where('saleable_id',$model->id)->first();
            if ($sale){
                $additional_materials = SaleAdditionalMaterial::where('sale_id',$sale->id)->get();
                $additional_materials = new SaleAdditionalMaterialCollection($additional_materials);
                $users = SaleUser::where('sale_id',$sale->id)->with(['user'])->get();
            }
        }elseif ($model->production_type == Product::ASSEMBLY){
            $assembly = Assembly::where('assemblyable_type','orders')->where('assemblyable_id',$model->id)->first();
            if ($assembly){
                $additional_materials = AssemblyAdditionalMaterial::where('assembly_id',$assembly->id)->get();
                $additional_materials = new AssemblyAdditionalMaterialCollection($additional_materials);
                $users = AssemblyUser::where('assembly_id',$assembly->id)->with(['user'])->get();
            }
        }

        $groups = self::getGroups($users);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'order' => new \App\Http\Resources\Order($model,true),
                    'additional_materials' => $additional_materials,
                    'employeeGroups'     => $groups
                ],
            ]
        ]);
    }

    public function update(OrderRequest $request, Order $order)
    {
        if (!$model = $order)
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

        $event = new UpdateOrderEvent();
        $event->setNumber($request['number']);
        $event->setDatetime($request['datetime']);
        $event->setProductionType($request['production_type']);
        $event->setPriorityId($request['priority_id']);
        $event->setStateId($request['state_id']);
        $event->setClientId($request['client_id']);
        $event->setBeginDate($request['begin_date']);
        $event->setEndDate($request['end_date']);
        $event->setContractClientId($request['contract_client_id']);
        $event->setOrder($model);

        if ($products = $request['order_products']){
            $event->setProducts($products);
        }

        if ($additional_materials = $request['additional_materials']){
            $event->setAdditionalMaterials($additional_materials);
        }

        if ($costs = $request['order_costs']){
            $event->setCosts($costs);
        }

        if ($employees = $request['employees']){
            $event->setEmployees($employees);
        }

        event($event);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.update_success',['name' => __('messages.order')]),
                'data'    => [
                ]
            ]
        ]);
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
                'message' => trans('messages.destroy_success',['name' => trans('messages.order')]),
            ]
        ]);
    }

    public function checkDate(){
        /**
         *  * begin_date
         *  * end_date
         * production_type
         * sale_id
         * assembly_id
         * checkOrder
         * order_id
         */

        $messages = [];
        $begin_date = null;
        $end_date = null;

        if ($b_time = \request()->get('begin_date'))
                $begin_date = Carbon::parse($b_time)->toDateString();
        if ($e_time = \request()->get('end_date'))
            $end_date = Carbon::parse($end_date)->toDateString();


        if ($type = \request()->get('production_type')){
            if ($type == Product::PRODUCTION){
                $sales = Sale::where(function($query) use ($begin_date, $end_date) {
                        return $query->orwhere(function ($query) use ($begin_date, $end_date) {
                            return $query->whereDate('begin_date','>',$begin_date)->whereDate('begin_date','<',$end_date);
                        })->orwhere(function ($query) use ($begin_date, $end_date) {
                            return $query->whereDate('begin_date','<=',$begin_date)->whereDate('end_date','>=',$end_date);
                        })->orwhere(function ($query) use ($begin_date, $end_date) {
                            return $query->whereDate('end_date','>',$begin_date)->whereDate('end_date','<',$end_date);
                        });
                    });
                if ($id = \request()->get('sale_id')){
                    $sales = $sales->where('id','!=',$id);
                }
                if ($order_id = \request()->get('order_id')){
                    $sales = $sales->where('saleable_id','!=',$order_id)->where('saleable_type','!=','orders');
                }
                $count = $sales->count();
                if ($count > 0){
                    array_push($messages,trans('messages.this_time_has_sale',['count' => $count]));
                }
            }elseif ($type == Product::ASSEMBLY){
                $assemblies = Assembly::where(function($query) use ($begin_date, $end_date) {
                    return $query->orwhere(function ($query) use ($begin_date, $end_date) {
                        return $query->whereDate('begin_date','>',$begin_date)->whereDate('begin_date','<',$end_date);
                    })->orwhere(function ($query) use ($begin_date, $end_date) {
                        return $query->whereDate('begin_date','<=',$begin_date)->whereDate('end_date','>=',$end_date);
                    })->orwhere(function ($query) use ($begin_date, $end_date) {
                        return $query->whereDate('end_date','>',$begin_date)->whereDate('end_date','<',$end_date);
                    });
                });
                if ($id = \request()->get('assembly_id')){
                    $assemblies = $assemblies->where('id','!=',$id);
                }
                if ($order_id = \request()->get('order_id')){
                    $assemblies = $assemblies->where('assemblyable_id','!=',$order_id)->where('assemblyable_type','!=','orders');
                }
                $count = $assemblies->count();
                if ($count > 0){
                    array_push($messages,trans('messages.this_time_has_assembly',['count' => $count]));
                }
            }
        }

        if (\request()->get('checkOrder',false) == true){
            $orders = Order::where(function($query) use ($begin_date, $end_date) {
                return $query->orwhere(function ($query) use ($begin_date, $end_date) {
                    return $query->whereDate('begin_date','>',$begin_date)->whereDate('begin_date','<',$end_date);
                })->orwhere(function ($query) use ($begin_date, $end_date) {
                    return $query->whereDate('begin_date','<=',$begin_date)->whereDate('end_date','>=',$end_date);
                })->orwhere(function ($query) use ($begin_date, $end_date) {
                    return $query->whereDate('end_date','>',$begin_date)->whereDate('end_date','<',$end_date);
                });
            });

            if ($id = \request()->get('order_id')){
                $orders = $orders->where('id','!=',$id);
            }

            $count = $orders->count();
            if ($count > 0){
                array_push($messages,trans('messages.this_time_has_order',['count' => $count]));
            }
        }

        if (count($messages) > 0){
            return $this->response->withArray([
                'result' => [
                    'success' => false,
                    'data'    => null
                ],
                'error' => [
                    'messages' => $messages,
                    'code'      => ApiResponse::BAD_REQUEST
                ]
            ])->setStatusCode(ApiResponse::BAD_REQUEST);
        }

        return $this->response->withArray([
            'result' => [
                'success' => true
            ]
        ]);
    }

    public function deleteAdditionalMaterial(){
        if (!$order = $this->model->find(\request()->get('order_id',null)))
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

        if ($order->production_type == Product::ASSEMBLY){
            if ($additional_material = AssemblyAdditionalMaterial::find(\request()->get('additional_material_id',null))){
                $additional_material->delete();
            }
        }elseif ($order->production_type == Product::PRODUCTION){
            if ($additional_material = SaleAdditionalMaterial::find(\request()->get('additional_material_id',null))){
                $additional_material->delete();
            }
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.destroy_success',['name' => __('messages.material')]),
                'data'    => []
            ]
        ]);
    }

    public function deleteProduct(){
        if (!$order = $this->model->find(\request()->get('order_id',null)))
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

        if ($order_product = OrderProduct::find(\request()->get('order_product_id',null))){
            $order_product->delete();
        }else{
            return $this->response->withArray([
                'result' => [
                    'success' => false,
                    'data'    => []
                ],
                'error' => [
                    'message' => __('messages.not_found',['name' => __('messages.product')]),
                ]
            ])->setStatusCode(ApiResponse::PAGE_NOT_FOUND);
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.destroy_success',['name' => __('messages.product')]),
                'data'    => []
            ]
        ]);
    }

    public function deleteCost(){
        if (!$order = $this->model->find(\request()->get('order_id',null)))
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

        if ($order_cost = OrderCost::find(\request()->get('order_cost_id',null))){
            $order_cost->delete();
        }else{
            return $this->response->withArray([
                'result' => [
                    'success' => false,
                    'data'    => []
                ],
                'error' => [
                    'message' => __('messages.not_found',['name' => __('messages.cost')]),
                ]
            ])->setStatusCode(ApiResponse::PAGE_NOT_FOUND);
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.destroy_success',['name' => __('messages.cost')]),
                'data'    => []
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
                $data  = ChartController::weekly(Order::class,'amount'); break;
            case self::MONTHLY:
                $data  = ChartController::monthly(Order::class,'amount'); break;
            case self::YEARLY:
                $data  = ChartController::yearly(Order::class,'amount'); break;
            case self::PERIOD:
                $data  = ChartController::period(Order::class, \request('from_date'), \request('to_date'), 'amount'); break;
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

    public function print(){

        if (!$model = $this->model->find(\request('id',null))){
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

        return view('order_invoice',['order' => $model]);
    }

    public function comments(){

        if (!$model = $this->model->find(\request()->get('order_id',null))){
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
            'model_id' => 'required|exists:orders,id',
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

    public function multipleDelete(Request $request){
        if (is_array($request['items'])) {
            Order::whereIn('id',$request['items'])->each(function($order){
                $order->delete();
            });
        }
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.destroy_success',['name' => trans('messages.order')]),
            ]
        ]);
    }


    public function getLastId(){
        $ls = Order::latest('id')->first();
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'last_id' => (($ls ? $ls->id : 0) + 1)
            ]
        ]);
    }
}
