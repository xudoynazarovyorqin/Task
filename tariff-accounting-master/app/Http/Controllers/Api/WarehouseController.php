<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Resources\WarehouseCollection;
use App\Warehouse;
use EllipseSynergie\ApiResponse\Laravel\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WarehouseController extends Controller
{
    protected $response;

    protected $warehouse;

    protected $per_page;

    protected $apiResponse;

    private $message_not_found;

    public function __construct(Response $response, ApiResponse $apiResponse,Warehouse $warehouse)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:warehouses.create')->only('store');
        $this->middleware('permission:warehouses.show')->only('show');
        $this->middleware('permission:warehouses.update')->only('update');
        $this->middleware('permission:warehouses.delete')->only(['destroy']);

        $this->response = $response;
        $this->apiResponse = $apiResponse;
        $this->warehouse = $warehouse;
        $this->per_page = request()->get( 'per_page',1000000);
        $this->message_not_found = trans('messages.not_found', ['name' => trans('messages.warehouse')]);
    }

    public function index()
    {
        $warehouses = $this->warehouse;

        if ($str = \request('search'))
        {
            $warehouses = $warehouses->search($str);
        }

        $warehouses = $warehouses->filter();
        $warehouses = $warehouses->sort()->paginate($this->per_page);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => new WarehouseCollection($warehouses)
            ]
        ]);
    }

    public function inventory(){

        $warehouses = $this->warehouse;

        if ($str = \request('search'))
        {
            $warehouses = $warehouses->search($str);
        }

        $warehouses = $warehouses->sort()->paginate($this->per_page);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'  => new \App\Http\Resources\Inventory\WarehouseCollection($warehouses)
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'              => 'required',
            'warehouse_type_id' => 'required',
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

        $warehouse = Warehouse::create([
            'name'                  => $request['name'],
            'warehouse_type_id'     => $request['warehouse_type_id'],
        ]);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.store_success',['name' => trans('messages.warehouse')]),
                'data'    => [
                    'warehouse' => $warehouse
                ],
            ]
        ]);
    }

    public function show(Warehouse $warehouse)
    {
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'warehouse'  => new \App\Http\Resources\Warehouse($warehouse)
                ]
            ]
        ]);
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $validator = Validator::make($request->all(), [
            'name'              => 'required',
            'warehouse_type_id' => 'required',
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

        $warehouse->update([
            'name'                  => $request['name'],
            'warehouse_type_id'     => $request['warehouse_type_id'],
        ]);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.update_success',['name' => trans('messages.warehouse')]),
                'data'    => [
                    'warehouse' => $warehouse
                ],
            ]
        ]);
    }

    public function destroy(Warehouse $warehouse)
    {
        try {
            $warehouse->delete();
        } catch (\Exception $e) {

        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.destroy_success',['name' => trans('messages.warehouse')]),
            ]
        ]);
    }

    public function getTypes(){
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'types' => $this->warehouse::types()
                ],
            ]
        ]);
    }
}
