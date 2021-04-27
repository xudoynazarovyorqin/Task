<?php

namespace App\Http\Controllers\Api;

use App\Assembly;
use App\Events\Models\CreateRealizationEvent;
use App\Exceptions\ApiModelNotFoundException;
use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\RealizationRequest;
use App\Http\Resources\RealizationCollection;
use App\Http\Resources\RealizationMaterialCollection;
use App\Http\Resources\ReservationCollection;
use App\Realization;
use App\RealizationMaterial;
use App\Reservation;
use App\Sale;
use App\WarehouseMaterial;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Http\Request;

class RealizationController extends Controller
{
    protected $response;
    protected $per_page;
    protected $realization;
    protected $apiResponse;
    private $message_not_found;

    public function __construct(Response $response, ApiResponse $apiResponse, Realization $realization)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:realizations.create')->only('store');
        $this->middleware('permission:realizations.show')->only('show');
        $this->middleware('permission:realizations.update')->only('update');
        $this->middleware('permission:realizations.delete')->only(['destroy']);

        $this->response = $response;
        $this->realization = $realization;
        $this->apiResponse = $apiResponse;
        $this->per_page = intval(request()->get('per_page' , 1000000));
        $this->message_not_found = trans('messages.not_found',['name' => __('messages.realization')]);
    }

    public function index()
    {
        $realizations = $this->realization;
        if ($str = \request('search'))
        {
            $realizations = $realizations->search($str);
        }

        $realizations = $realizations->filter();
        $realizations = $realizations->sort()->paginate($this->per_page);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => new RealizationCollection($realizations)
            ]
        ]);
    }

    public function store(RealizationRequest $request)
    {
        $event = new CreateRealizationEvent();

        $event->setDatetime($request->get('datetime',null));
        $event->setUserId($request->get('user_id',null));
        $event->setRealizationableId($request->get(Realization::ABLE_ID,null));
        $event->setRealizationableType($request->get(Realization::ABLE_TYPE,null));

         if ($items = $request->get('items',null)){
             if (is_array($items)){
                 $event->setItems($items);
             }
         }

         event($event);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.store_success',['name' => trans('messages.Realization')]),
                'data'    => [],
            ]
        ]);
    }

    public function show(Realization $realization)
    {
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'realization'   => new \App\Http\Resources\Realization($realization,true)
                ]
            ]
        ]);
    }

    public function update(RealizationRequest $request, Realization $realization)
    {
        $realization->update([
            'user_id'    => $request->get('user_id',null),
            'datetime'   => $request->get('datetime',null),
            Realization::ABLE_TYPE => $request->get(Realization::ABLE_TYPE,null),
            Realization::ABLE_ID => $request->get(Realization::ABLE_ID,null),
        ]);

        $realization->realization_materials()->each(function ($item){
            $item->delete();
        });

        if ($items = $request->get('items',null)){
            if (is_array($items)){
                foreach ($items as $item){
                    RealizationMaterial::create([
                        'realization_id' => $realization->id,
                        'material_id'    => $item['material_id'],
                        'quantity'       => $item['quantity'],
                        'issued_from_booked' => $item['issued_from_booked']
                    ]);
                }
            }
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.update_success',['name' => trans('messages.Realization')]),
                'data'    => [],
            ]
        ]);
    }

    public function destroy(Realization $realization)
    {
        try {
            $realization->delete();
        } catch (\Exception $e) {
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.destroy_success',['name' => trans('messages.Realization')]),
                'data'    => [],
            ]
        ]);
    }

    public function getDocuments(){
        $realizationable_type = request()->get(Realization::ABLE_TYPE,null);

        $sales = [];
        $assemblies = [];
        if ($realizationable_type){
              if ($realizationable_type == Sale::TABLE_NAME){
                  $sales = Sale::whereHas('sale_materials',function ($query){
                        return $query->whereRaw('total - ready > 0');
                  })->get();
              }elseif ($realizationable_type == Assembly::TABLE_NAME){
                  $assemblies = Assembly::whereHas('assembly_materials',function ($query){
                      return $query->whereRaw('total - ready > 0');
                  })->get();
              }
        }else{
            $sales = Sale::whereHas('sale_materials',function ($query){
                return $query->whereRaw('total - ready > 0');
            })->get();
            $assemblies = Assembly::whereHas('assembly_materials',function ($query){
                return $query->whereRaw('total - ready > 0');
            })->get();
        }

        $collection = collect();

        if ( count($sales) ) {
            foreach ($sales as $sale){
                $item = [];
                $item[Realization::ABLE_ID] = $sale->id;
                $item[Realization::ABLE_TYPE] = Sale::TABLE_NAME;
                $item['datetime'] = $sale->datetime ? date(Controller::ELEMENT_DATE_TIME_FORMAT,strtotime($sale->datetime)): '';
                $item['status']   = $sale->state ? $sale->state->state : '';
                $collection->push($item);
            }
        }

        if ( count($assemblies) ){
            foreach ($assemblies as $assembly){
                $item = [];
                $item[Realization::ABLE_ID] = $assembly->id;
                $item[Realization::ABLE_TYPE] = Assembly::TABLE_NAME;
                $item['datetime'] = $assembly->datetime ? date(Controller::ELEMENT_DATE_TIME_FORMAT,strtotime($assembly->datetime)): '';
                $item['status']   = $assembly->state ? $assembly->state->state : '';
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

        $type = request()->get(Realization::ABLE_TYPE,null);
        $id = request()->get(Realization::ABLE_ID,null);

        if ($type && $id){
            $reservations = Reservation::where(Reservation::ABLE_TYPE,$type)
                ->where(Reservation::SOURCEABLE_TYPE,WarehouseMaterial::TABLE_NAME)
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

    public function items(){

        if (!$realization = $this->realization->find(request()->get('realization_id')))
            throw new ApiModelNotFoundException(__('messages.not_found',['name' => __('messages.Realization')]));

        $realization_materials = RealizationMaterial::where('realization_id',$realization->id)->get();

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => new RealizationMaterialCollection($realization_materials)
            ]
        ]);
    }

    public function multipleDelete(Request $request){
        if (is_array($request['items'])) {
            Realization::whereIn('id',$request['items'])->each(function($sale){
                $sale->delete();
            });
        }
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.destroy_success',['name' => trans('messages.Realization')]),
            ]
        ]);
    }

    public function getLastId(){
        $ls = Realization::latest('id')->first();
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'last_id' => (($ls ? $ls->id : 0) + 1)
            ]
        ]);
    }
}
