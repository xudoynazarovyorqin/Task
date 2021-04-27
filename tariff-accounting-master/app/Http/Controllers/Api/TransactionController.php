<?php

namespace App\Http\Controllers\Api;

use App\ApplicationPart;
use App\Buy;
use App\BuyReadyProduct;
use App\Events\Models\CreateTransactionEvent;
use App\Events\Money\Mudofa\DistributionMoneyEvent;
use App\Events\Update\UpdateTransactionEvent;
use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Requests\TransactionRequest;
use App\Http\Resources\Relation\Client;
use App\Http\Resources\Relation\ContractClient;
use App\Http\Resources\Relation\ContractProvider;
use App\Http\Resources\Relation\State;
use App\Http\Resources\TransactionCollection;
use App\Order;
use App\Product;
use App\Provider;
use App\SaleReadyProduct;
use App\Transaction;
use Carbon\Carbon;
use EllipseSynergie\ApiResponse\Laravel\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected $response;

    protected $transaction;

    protected $per_page;

    protected $apiResponse;

    private $message_not_found;

    private $model;

    public function __construct(Response $response, ApiResponse $apiResponse,Transaction $transaction)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:transactions.create')->only('store');
        $this->middleware('permission:transactions.show')->only('show');
        $this->middleware('permission:transactions.update')->only('update');
        $this->middleware('permission:transactions.delete')->only(['destroy']);

        $this->response = $response;
        $this->apiResponse = $apiResponse;
        $this->transaction = $transaction;
        $this->per_page = request()->get('per_page',1000000);
        $this->message_not_found = trans('messages.not_found', ['name' => trans('messages.transaction')]);
        $this->model = $transaction;
    }

    public function index()
    {
        $transactions = $this->transaction;

        if ($str = \request('search'))
            $transactions = $transactions->search($str);

        $transactions = $transactions->whereIn(Transaction::ABLE_TYPE,[\App\Client::TABLE_NAME,Provider::TABLE_NAME]);

        $transactions = $transactions->filter();
        $transactions = $transactions->sort()->paginate($this->per_page);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => new TransactionCollection($transactions)
            ]
        ]);
    }

    public function store(TransactionRequest $request)
    {
        $event = new CreateTransactionEvent();
        $event->setTransactionableType($request->get('transactionable_type',''));
        $event->setTransactionableId($request->get('transactionable_id',null));
        $event->setContractableType($request->get('contractable_type',''));
        $event->setContractableId($request->get('contractable_id',null));
        $event->setPaymentTypeId($request->get('payment_type_id',null));
        $event->setDatetime($request->get('datetime',null));
        $event->setAmount($request->get('amount',0.0));
        $event->setCurrencyId($request->get('currency_id',null));
        $event->setRate($request->get('rate',1));
        $event->setComment($request->get('comment',''));
        $event->setDebit($request->get('debit',0));
        $event->setScoreId($request->get('score_id',0));

        if (is_array($request->get('relatedItems')))
            $event->setRelatedItems($request->get('relatedItems'));

        event($event);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.store_success',['name' => __('messages.transaction')]),
            ]
        ]);
    }

    public function show(Transaction $transaction)
    {
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'transaction' => new \App\Http\Resources\Transaction($transaction,true),
                ],
            ]
        ]);
    }

    public function update(TransactionRequest $request, Transaction $transaction)
    {
        $event = new UpdateTransactionEvent();
        $event->setId($transaction->id);
        $event->setTransactionableId($request->get('transactionable_id',null));
        $event->setContractableId($request->get('contractable_id',null));
        $event->setPaymentTypeId($request->get('payment_type_id',null));
        $event->setDatetime($request->get('datetime',null));
        $event->setAmount($request->get('amount',0.0));
        $event->setCurrencyId($request->get('currency_id',null));
        $event->setRate($request->get('rate',1));
        $event->setComment($request->get('comment',''));
        $event->setScoreId($request->get('score_id',0));

        if (is_array($request->get('relatedItems')))
            $event->setRelatedItems($request->get('relatedItems'));

        event($event);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.update_success',['name' => __('messages.transaction')]),
            ]
        ]);
    }

    public function destroy(Transaction $transaction)
    {
        try {
            $transaction->delete();
        } catch (\Exception $e) {
            return $this->response->withArray([
                'result' => [
                    'success' => true,
                    'message' => trans('messages.',['name' => trans('messages.sale')]),
                ]
            ]);
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.destroy_success',['name' => trans('messages.sale')]),
            ]
        ]);
    }

    public function getLastId(){
        $ls = Transaction::latest('id')->first();
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'last_id' => (($ls ? $ls->id : 0) + 1)
            ]
        ]);
    }

    public function getOutgoingDocuments(){
        $sales = SaleReadyProduct::with('contract_client','state','client')->paid(false)->filter()->get();
        $orders = Order::with('contract_client','state','client')->paid(false)->filter()->get();


        $list = [];

        foreach ($sales as $sale){
            array_push($list,[
               'paymentable_type'   => SaleReadyProduct::TABLE_NAME,
               'paymentable_id'     => $sale->id,
               'datetime'           => $sale->datetime ? date(Controller::ELEMENT_DATE_TIME_FORMAT,strtotime($sale->datetime)) : '',
               'contract'           => $sale->contract_client ? new ContractClient($sale->contract_client) : null,
               'state'              => $sale->state ? new State($sale->state) : null,
               'client'             => $sale->client ? new Client($sale->client) : null,
               'total_amount'       => floatval($sale->total_price),
               'paid_amount'        => floatval($sale->paid_price),
            ]);
        }

        foreach ($orders as $order){
            array_push($list,[
               'paymentable_type'   => Order::TABLE_NAME,
               'paymentable_id'     => $order->id,
               'datetime'           => $order->datetime ? date(Controller::ELEMENT_DATE_TIME_FORMAT,strtotime($order->datetime)) : '',
               'contract'           => $order->contract_client ? new ContractClient($order->contract_client) : null,
               'state'              => $order->state ? new State($order->state) : null,
               'client'             => $order->client ? new Client($order->client) : null,
               'total_amount'       => floatval($order->amount),
               'paid_amount'        => floatval($order->paid),
            ]);
        }
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data' => $list
            ]
        ]);
    }

    public function getIncomingDocuments(){
        $buys = Buy::with(['contract_provider','status','provider'])->paid(false)->filter()->get();
        $buy_ready_products = BuyReadyProduct::with(['contract_provider','status','provider'])->paid(false)->filter()->get();

        $list = [];

        foreach ($buys as $buy){
            array_push($list,[
               'paymentable_type'   => Buy::TABLE_NAME,
               'paymentable_id'     => $buy->id,
               'datetime'           => $buy->datetime ? date(Controller::ELEMENT_DATE_TIME_FORMAT,strtotime($buy->datetime)) : '',
               'contract'           => $buy->contract_provider ? new ContractProvider($buy->contract_provider) : null,
               'status'             => $buy->status ? new State($buy->status) : null,
               'provider'           => $buy->provider ? new \App\Http\Resources\Relation\Provider($buy->provider) : null,
               'total_amount'       => floatval($buy->total_price),
               'paid_amount'        => floatval($buy->paid_price),
            ]);
        }

        foreach ($buy_ready_products as $buy_ready_product){
            array_push($list,[
               'paymentable_type'   => BuyReadyProduct::TABLE_NAME,
               'paymentable_id'     => $buy_ready_product->id,
               'datetime'           => $buy_ready_product->datetime ? date(Controller::ELEMENT_DATE_TIME_FORMAT,strtotime($buy_ready_product->datetime)) : '',
               'contract'           => $buy_ready_product->contract_provider ? new ContractProvider($buy_ready_product->contract_provider) : null,
               'status'             => $buy_ready_product->status ? new State($buy_ready_product->status) : null,
               'provider'            => $buy_ready_product->provider ? new \App\Http\Resources\Relation\Provider($buy_ready_product->provider) : null,
               'total_amount'       => floatval($buy_ready_product->total_price),
               'paid_amount'        => floatval($buy_ready_product->paid_price),
            ]);
        }
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data' => $list
            ]
        ]);
    }

    public function multipleDelete(Request $request){
        if (is_array($request['items'])) {
            Transaction::whereIn('id',$request['items'])->each(function($item){
                $item->delete();
            });
        }
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.destroy_success',['name' => trans('messages.transaction')]),
            ]
        ]);
    }
}
