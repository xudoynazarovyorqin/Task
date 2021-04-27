<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContractProviderRequest;
use App\Http\Resources\ContractProviderResource;
use App\ContractProvider;
use App\Http\Resources\Inventory\ContractProviderCollection;
use EllipseSynergie\ApiResponse\Laravel\Response;

class ContractProviderController extends Controller
{
    protected $response;

    protected $per_page;

    protected $contractProvider;

    protected $apiResponse;

    private $message_not_found;

    public function __construct(Response $response, ApiResponse $apiResponse, ContractProvider $contractProvider)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:contractProviders.create')->only('store');
        $this->middleware('permission:contractProviders.show')->only('show');
        $this->middleware('permission:contractProviders.update')->only('update');
        $this->middleware('permission:contractProviders.delete')->only(['destroy']);

        $this->response = $response;
        $this->contractProvider = $contractProvider;
        $this->apiResponse = $apiResponse;
        $this->per_page = request()->get('per_page',1000000);
        $this->message_not_found = trans('messages.not_found',['name' => __('messages.contract')]);
    }

    public function index()
    {
        $contractProviders = $this->contractProvider;
        if ($str = \request('search'))
        {
            $contractProviders = $contractProviders->search($str);
        }

        $contractProviders = $contractProviders->filter();
        $contractProviders = $contractProviders->sort()->paginate($this->per_page);

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'    => new ContractProviderResource($contractProviders)
           ]
        ]);
    }

    public function inventory()
    {
        $contractProviders = $this->contractProvider;
        if ($str = \request('search'))
        {
            $contractProviders = $contractProviders->search($str);
        }

        $contractProviders = $contractProviders->filter();
        $contractProviders = $contractProviders->sort()->paginate($this->per_page);

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'  => new ContractProviderCollection($contractProviders)
           ],
        ]);
    }

    public function show(ContractProvider $contractProvider)
    {
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'contractProvider' => new \App\Http\Resources\ContractProvider($contractProvider,true)
                ]
            ]
        ]);
    }

    public function store(ContractProviderRequest $request)
    {
        $contractProvider = ContractProvider::create($request->all());

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.store_success',['name' => __('messages.contract')]),
                'data'    => [
                    'contractProvider' => new \App\Http\Resources\ContractProvider($contractProvider)
                ]
            ]
        ]);
    }

    public function update(ContractProviderRequest $request, ContractProvider $contractProvider)
    {
        $contractProvider->update([
           'number'             => $request['number'],
           'begin_date'         => $request['begin_date'],
           'provider_id'        => $request['provider_id'],
           'status_id'          => $request['status_id'],
           'sum'                => $request['sum'],
           'comment'            => $request['comment'],
           'parent_id'          => $request['parent_id']
        ]);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.update_success',['name' => __('messages.contract')]),
                'data'    => [
                    'contractProvider'  => new \App\Http\Resources\ContractProvider($contractProvider)
                ]
            ]
        ]);
    }

    public function destroy(ContractProvider $contractProvider)
    {
        try {
            $contractProvider->delete();
        } catch (\Exception $e) {
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.destroy_success',['name' => __('messages.contract')]),
                'data'    => [
                ]
            ]
        ]);
    }
}
