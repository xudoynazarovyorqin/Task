<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\CurrencyRequest;
use App\Currency;
use App\Http\Resources\Inventory\CurrencyCollection;
use EllipseSynergie\ApiResponse\Laravel\Response;

class CurrencyController extends Controller
{
    protected $response;

    protected $per_page;

    protected $currency;

    protected $apiResponse;

    private $message_not_found;

    public function __construct(Response $response, ApiResponse $apiResponse, Currency $currency)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:currencies.create')->only('store');
        $this->middleware('permission:currencies.show')->only('show');
        $this->middleware('permission:currencies.update')->only('update');
        $this->middleware('permission:currencies.delete')->only(['destroy']);

        $this->response = $response;
        $this->currency = $currency;
        $this->apiResponse = $apiResponse;
        $this->per_page = request()->get('per_page',1000000);
        $this->message_not_found = trans('messages.not_found',['name' => __('messages.currency')]);
    }

    public function index()
    {
        $currencies = $this->currency;
        if ($str = \request('search'))
        {
            $currencies = $currencies->search($str);
        }

        $currencies = $currencies->filter();
        $currencies = $currencies->sort()->paginate($this->per_page);

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'    => new \App\Http\Resources\CurrencyCollection($currencies)
           ]
        ]);
    }


    public function inventory()
    {
        $currencies = $this->currency;
        if ($str = \request('search'))
        {
            $currencies = $currencies->search($str);
        }

        $currencies = $currencies->filter();
        $currencies = $currencies->sort()->paginate($this->per_page);

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'  => new CurrencyCollection($currencies)
           ],
        ]);
    }

    public function show(Currency $currency)
    {
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'currency' => new \App\Http\Resources\Currency($currency)
                ]
            ]
        ]);
    }

    public function store(CurrencyRequest $request)
    {
        $currency = Currency::create($request->all());

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.store_success',['name' => __('messages.currency')]),
                'data'    => [
                    'currency' => new \App\Http\Resources\Currency($currency)
                ]
            ]
        ]);
    }


    public function update(CurrencyRequest $request, Currency $currency)
    {
        $currency->update($request->all());

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.update_success',['name' => __('messages.currency')]),
                'data'    => [
                    'currency' => new \App\Http\Resources\Currency($currency)
                ]
            ]
        ]);
    }

    public function destroy(Currency $currency)
    {
        try {
            $currency->delete();
        } catch (\Exception $e) {

        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.destroy_success',['name' => __('messages.currency')]),
                'data'    => [
                ]
            ]
        ]);
    }
}
