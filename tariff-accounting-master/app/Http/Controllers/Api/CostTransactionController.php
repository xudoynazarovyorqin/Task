<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionCollection;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Http\Request;
use App\Transaction;
use App\Cost;

class CostTransactionController extends Controller
{

    protected $response;

    protected $per_page;

    protected $transaction;

    protected $apiResponse;

    private $message_not_found;

    public function __construct(Response $response, ApiResponse $apiResponse, Transaction $transaction)
    {
        $this->middleware('auth:api');

        $this->response = $response;
        $this->transaction = $transaction;
        $this->apiResponse = $apiResponse;
        $this->per_page = request()->get('per_page',1000000);
        $this->message_not_found = trans('messages.not_found',['name' => __('messages.transaction')]);
    }

    public function index()
    {
        $transactions = $this->transaction;
        $transactions = $transactions->where(Transaction::ABLE_TYPE,Cost::TABLE_NAME);

        if ($str = \request('search'))
        {
            $transactions = $transactions->search($str);
        }

        $transactions = $transactions->filter();
        $transactions = $transactions->costs();
        $transactions = $transactions->sort()->paginate($this->per_page);

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'    => new TransactionCollection($transactions)
           ]
        ]);
    }

    public function show($id)
    {
    }

    public function store(Request $request)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {

    }
}
