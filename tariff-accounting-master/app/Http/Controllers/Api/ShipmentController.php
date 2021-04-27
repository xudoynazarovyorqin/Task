<?php

namespace App\Http\Controllers\Api;

use App\Assembly;
use App\Events\Models\CreateShipmentEvent;
use App\Events\Update\UpdateShipmentEvent;
use App\Exceptions\ApiModelNotFoundException;
use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\ShipmentRequest;
use App\Http\Resources\ReservationCollection;
use App\Http\Resources\ShipmentCollection;
use App\Http\Resources\ShipmentProductCollection;
use App\Order;
use App\Realization;
use App\Reservation;
use App\SaleReadyProduct;
use App\WarehouseProduct;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Shipment;
use App\ShipmentProduct;

class ShipmentController extends Controller
{
    protected $response;

    protected $per_page;

    protected $shipment;

    protected $apiResponse;

    private $message_not_found;

    public function __construct(Response $response, ApiResponse $apiResponse, Shipment $shipment)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:shipments.create')->only('store');
        $this->middleware('permission:shipments.show')->only('show');
        $this->middleware('permission:shipments.update')->only('update');
        $this->middleware('permission:shipments.delete')->only(['destroy','multipleDelete']);

        $this->response = $response;
        $this->shipment = $shipment;
        $this->apiResponse = $apiResponse;
        $this->per_page = request()->get('per_page',10000) ;
        $this->message_not_found = trans('messages.not_found',['name' => __('messages.shipment')]);
    }

    public function index()
    {
        $shipments = $this->shipment;
        if ($str = \request('search'))
        {
            $shipments = $shipments->search($str);
        }

        $shipments = $shipments->filter();
        $shipments = $shipments->sort()->paginate($this->per_page);

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'    => new ShipmentCollection($shipments)
           ]
        ]);
    }

    public function store(ShipmentRequest $request)
    {
        $event = new CreateShipmentEvent();
        $event->setShipmentableId($request->get(Shipment::ABLE_ID,null));
        $event->setShipmentableType($request->get(Shipment::ABLE_TYPE,null));
        $event->setDatetime($request->get('datetime',null));
        $event->setUserId(auth()->id());
        $event->setComment($request->get('comment',null));

        $event->setItems($request->get('items',[]));

        event($event);
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.store_success',['name' => __('messages.shipment')]),
            ]
        ]);
    }

    public function show(Shipment $shipment)
    {
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'shipment' => new \App\Http\Resources\Shipment($shipment,true)
                ]
            ]
        ]);
    }

    public function update(ShipmentRequest $request,Shipment $shipment)
    {
        $event = new UpdateShipmentEvent($shipment);
        $event->setShipmentableId($request->get(Shipment::ABLE_ID,null));
        $event->setShipmentableType($request->get(Shipment::ABLE_TYPE,null));
        $event->setDatetime($request->get('datetime',null));
        $event->setUserId(auth()->id());
        $event->setComment($request->get('comment',null));

        $event->setItems($request->get('items',[]));

        event($event);
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.update_success',['name' => __('messages.shipment')]),
            ]
        ]);
    }


    public function destroy(Shipment $shipment)
    {
        try {
            $shipment->delete();
        } catch (\Exception $e) {

        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.destroy_success',['name' => __('messages.shipment')]),
                'data'    => [
                ]
            ]
        ]);
    }

    public function multipleDelete(Request $request){

        if (is_array($request['items'])) {
            Shipment::whereIn('id',$request['items'])->each(function($shipment){
                $shipment->delete();
            });
        }
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.destroy_success',['name' => trans('messages.shipment')]),
            ]
        ]);
    }

    public function print(){
        if (!$model = $this->shipment->find(\request('shipment_id'))){
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

        return view('shipment_invoice',['shipment' => $model]);
    }

    public function getDocuments(){
        $type = request()->get(Shipment::ABLE_TYPE,null);

        $assemblies = [];
        $orders = [];
        $sales = [];
        if ($type){
                if ($type == Assembly::TABLE_NAME){
                    $assemblies = Assembly::whereHas('assembly_products',function ($query){
                        return $query->whereRaw('total - ready > 0');
                    })->get();
            }elseif ($type == Order::TABLE_NAME){
                    $orders = Order::whereHas('products',function ($query){
                        return $query->whereRaw('quantity - ready > 0');
                    })->get();
                }elseif ($type == SaleReadyProduct::TABLE_NAME){
                    $sales = SaleReadyProduct::whereHas('items',function ($query){
                        return $query->whereRaw('quantity - shipped > 0');
                    })->get();
                }
        }else{
            $assemblies = Assembly::whereHas('assembly_products',function ($query){
                return $query->whereRaw('total - ready > 0');
            })->get();
            $orders = Order::whereHas('products',function ($query){
                return $query->whereRaw('quantity - ready > 0');
            })->get();
            $sales = SaleReadyProduct::whereHas('items',function ($query){
                return $query->whereRaw('quantity - shipped > 0');
            })->get();
        }

        $collection = collect();

        if ( count($assemblies) ){
            foreach ($assemblies as $assembly){
                $item = [];
                $item[Shipment::ABLE_ID] = $assembly->id;
                $item[Shipment::ABLE_TYPE] = Assembly::TABLE_NAME;
                $item['datetime'] = $assembly->datetime ? date(Controller::ELEMENT_DATE_TIME_FORMAT,strtotime($assembly->datetime)): '';
                $item['status']   = $assembly->state ? $assembly->state->state : '';
                $collection->push($item);
            }
        }

        if ( count($orders) ){
            foreach ($orders as $order){
                $item = [];
                $item[Shipment::ABLE_ID] = $order->id;
                $item[Shipment::ABLE_TYPE] = Order::TABLE_NAME;
                $item['datetime'] = $order->datetime ? date(Controller::ELEMENT_DATE_TIME_FORMAT,strtotime($order->datetime)): '';
                $item['status']   = $order->state ? $order->state->state : '';
                $collection->push($item);
            }
        }

        if ( count($sales) ){
            foreach ($sales as $sale){
                $item = [];
                $item[Shipment::ABLE_ID] = $sale->id;
                $item[Shipment::ABLE_TYPE] = SaleReadyProduct::TABLE_NAME;
                $item['datetime'] = $sale->datetime ? date(Controller::ELEMENT_DATE_TIME_FORMAT,strtotime($sale->datetime)): '';
                $item['status']   = $sale->state ? $sale->state->state : '';
                $collection->push($item);
            }
        }

        $sorted = $collection->sortByDesc('datetime')->values()->all();

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'documents' => $sorted
                ]
            ]
        ]);
    }

    public function getReservations(){

        $type = request()->get(Shipment::ABLE_TYPE,null);
        $id = request()->get(Shipment::ABLE_ID,null);

        if ($type && $id){
            $reservations = Reservation::where(Reservation::ABLE_TYPE,$type)
                ->where(Reservation::SOURCEABLE_TYPE,WarehouseProduct::TABLE_NAME)
                ->where(Reservation::ABLE_ID,$id)
                ->whereRaw('quantity  - issued > 0')
                ->paginate(Controller::DEFAULT_PER_PAGE_PAGINATION);

            return $this->response->withArray([
                'result' => [
                    'success' => true,
                    'data'    => new ReservationCollection($reservations)
                ]
            ]);
        }

        return $this->response->withArray([
            'result' => [
                'success' => false,
                'data'    => []
            ],
            'error' => [
                'message' => __('messages.The document will not find'),
                'code'    => ApiResponse::BAD_REQUEST
            ]
        ])->setStatusCode(ApiResponse::BAD_REQUEST);
    }

    public function getLastId(){
        $ls = Shipment::latest('id')->first();
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'last_id' => (($ls ? $ls->id : 0) + 1)
            ]
        ]);
    }

    public function items(){

        if (!$shipment = $this->shipment->find(request()->get('shipment_id')))
            throw new ApiModelNotFoundException(__('messages.not_found',['name' => __('messages.Realization')]));

        $products = ShipmentProduct::where('shipment_id',$shipment->id)->get();

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => new ShipmentProductCollection($products)
            ]
        ]);
    }
}
