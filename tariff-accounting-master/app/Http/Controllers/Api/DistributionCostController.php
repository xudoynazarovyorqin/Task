<?php

namespace App\Http\Controllers\Api;

use App\Cost;
use App\DistributionCost;
use App\DistributionTransaction;
use App\Events\Models\CreateDistributionCostEvent;
use App\Events\Update\UpdateDistributionCostEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\DistributionCostRequest;
use App\Http\Resources\DistributionCostCollection;
use App\Transaction;
use App\WarehouseMaterial;
use App\WarehouseProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Support\Facades\Log;

class DistributionCostController extends Controller
{
    protected $model;

    protected $per_page;

    private $message_not_found;

    private $listIndex;

    private $modelIndex;

    protected $response;

    public function __construct(Response $response, DistributionCost $model)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:distribution_costs.create')->only('store');
        $this->middleware('permission:distribution_costs.show')->only('show');
        $this->middleware('permission:distribution_costs.edit')->only('edit');
        $this->middleware('permission:distribution_costs.print')->only('print');
        $this->middleware('permission:distribution_costs.update')->only('update');
        $this->middleware('permission:distribution_costs.delete')->only(['destroy']);

        $this->response = $response;
        $this->model = $model;
        $this->per_page = request()->get('per_page',10000);
        $this->message_not_found = trans('messages.not_found',['name' => trans('messages.Distribution of cost')]);
        $this->listIndex = 'distribution_costs';
        $this->modelIndex = 'distribution_cost';
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
                'data'    => new DistributionCostCollection($list)
            ]
        ]);
    }

    public function store(DistributionCostRequest $request)
    {
        $event = new CreateDistributionCostEvent();
        $event->setDatetime($request->get('datetime',null));
        $event->setType($request->get('type',null));
        $event->setFromDate($request->get('from_date',null));
        $event->setToDate($request->get('to_date',null));
        $event->setTransactions($request->get('transactions',[]));
        $event->setItems($request->get('items',[]));
        event($event);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.store_success',['name' => trans('messages.Distribution of costs')]),
            ]
        ]);
    }

    public function show(DistributionCost $distributionCost)
    {
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'distribution_cost' => new \App\Http\Resources\DistributionCost($distributionCost,true),
                ],
            ]
        ]);
    }

    public function update(DistributionCostRequest $request, DistributionCost $distributionCost)
    {
        $event = new UpdateDistributionCostEvent();
        $event->setId($distributionCost->id);
        $event->setDatetime($request->get('datetime',null));
        $event->setType($request->get('type',null));
        $event->setFromDate($request->get('from_date',null));
        $event->setToDate($request->get('to_date',null));
        $event->setTransactions($request->get('transactions',[]));
        $event->setItems($request->get('items',[]));

        event($event);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.update_success',['name' => __('messages.Distribution of costs')]),
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
                'message' => trans('messages.destroy_success',['name' => trans('messages.Distribution of costs')]),
            ]
        ]);
    }

    public function multipleDelete(Request $request){
        if (is_array($request['items'])) {
            DistributionCost::whereIn('id',$request['items'])->each(function($item){
                $item->delete();
            });
        }
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.destroy_success',['name' => trans('messages.Distribution of costs')]),
            ]
        ]);
    }

    public function getWarehouseProducts(){
        $warehouse_products = WarehouseProduct::with(['product:id,name', 'currency:id,name,symbol'])
            ->where(WarehouseProduct::ABLE_TYPE,'like','%' . \request('warehouse_productable_type') . '%')
            ->whereDate('created_at','>=',Carbon::parse(\request('from_date'))->toDateString())
            ->whereDate('created_at','<=',Carbon::parse(\request('to_date'))->toDateString())
            ->get();

        $list = [];

        foreach ($warehouse_products as $warehouse_product){
            array_push($list,[
                'id'                            => $warehouse_product->id,
                'product'                       => $warehouse_product->product,
                'currency'                      => $warehouse_product->currency,
                'warehouse_productable_type'    => $warehouse_product->warehouse_productable_type,
                'remainder'                     => floatval($warehouse_product->remainder),
                'buy_price'                     => floatval($warehouse_product->buy_price),
                'rate'                          => floatval($warehouse_product->rate),
                'created_at'                    => $warehouse_product->created_at ? date(Controller::ELEMENT_DATE_TIME_FORMAT,strtotime($warehouse_product->created_at)) : '',
            ]);
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'warehouse_products' => $list
                ]
            ]
        ]);
    }

    public function getWarehouseMaterials(){
        $warehouse_materials = WarehouseMaterial::with(['material:id,name', 'currency:id,name,symbol'])
            ->where(WarehouseMaterial::ABLE_TYPE,'like','%' . \request('warehouse_materialable_type') . '%')
            ->whereDate('created_at','>=',Carbon::parse(\request('from_date'))->toDateString())
            ->whereDate('created_at','<=',Carbon::parse(\request('to_date'))->toDateString())
            ->get();

        $list = [];

        foreach ($warehouse_materials as $warehouse_material){
            array_push($list,[
                'id'                            => $warehouse_material->id,
                'material'                      => $warehouse_material->material,
                'currency'                      => $warehouse_material->currency,
                'warehouse_materialable_type'   => $warehouse_material->warehouse_materialable_type,
                'remainder'                     => floatval($warehouse_material->remainder),
                'buy_price'                     => floatval($warehouse_material->buy_price),
                'rate'                          => floatval($warehouse_material->rate),
                'created_at'                    => $warehouse_material->created_at ? date(Controller::ELEMENT_DATE_TIME_FORMAT,strtotime($warehouse_material->created_at)) : '',
            ]);
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'warehouse_materials' => $list
                ]
            ]
        ]);
    }

    public function getCostTransactions(){
        $cost_transactions = Transaction::with(['transactionable', 'currency:id,name,symbol'])
            ->where(Transaction::ABLE_TYPE, Cost::TABLE_NAME)
            ->whereHasMorph('transactionable', Cost::class, function ($query) { //costi raspredelyonniy bogan transaction lani olish
                return $query->where('is_distribution', true);
            })
            ->whereDate('datetime','>=',Carbon::parse(\request('from_date'))->toDateString())
            ->whereDate('datetime','<=',Carbon::parse(\request('to_date'))->toDateString())
            ->whereRaw('(real_amount - distribution_amount) > 0')
            ->get();

        $list = [];

        foreach ($cost_transactions as $cost_transaction){
            $distribution_amount = floatval($cost_transaction->distribution_amount);

            if( \request('is_edit', false) ) {
                $old_transactions = DistributionTransaction::select('transaction_id', 'price')->where('distribution_cost_id', \request('distribution_cost_id', null))->get()->toArray();
                $key = array_search($cost_transaction->id, array_column($old_transactions, 'transaction_id'));

                if( $key !== false && $old_transactions[$key] ) {
                    $distribution_amount -= floatval($old_transactions[$key]['price']);
                }
            }

            array_push($list,[
                'transaction_id'                => $cost_transaction->id,
                'transactionable'               => $cost_transaction->transactionable,
                'currency'                      => $cost_transaction->currency,
                'rate'                          => floatval($cost_transaction->rate),
                'amount'                        => floatval($cost_transaction->amount),
                'real_amount'                   => floatval($cost_transaction->real_amount),
                'distribution_amount'           => $distribution_amount,
                'datetime'                      => $cost_transaction->datetime ? date(Controller::ELEMENT_DATE_TIME_FORMAT,strtotime($cost_transaction->datetime)) : '',
            ]);
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'cost_transactions' => $list
                ]
            ]
        ]);
    }

    public function getLastId(){
        $ls = DistributionCost::latest('id')->first();
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'last_id' => (($ls ? $ls->id : 0) + 1)
            ]
        ]);
    }
}
