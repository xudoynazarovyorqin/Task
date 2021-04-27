<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Interfaces\WarehouseTypeInterface;
use App\Http\Resources\Inventory\WarehouseTypeCollection;
use App\WarehouseType;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class WarehouseTypeController extends Controller
{
    protected $response;

    protected $per_page;

    protected $warehouseType;

    protected $apiResponse;

    private $message_not_found;

    public function __construct(Response $response, ApiResponse $apiResponse, WarehouseType $warehouseType)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:warehouseTypes.create')->only('store');
        $this->middleware('permission:warehouseTypes.show')->only('show');
        $this->middleware('permission:warehouseTypes.update')->only('update');
        $this->middleware('permission:warehouseTypes.delete')->only(['destroy']);

        $this->response = $response;
        $this->warehouseType = $warehouseType;
        $this->apiResponse = $apiResponse;
        $this->per_page = request()->get('per_page',1000000);
        $this->message_not_found = trans('strings.warehouse_type_not_found');
    }


    public function index()
    {
        $warehouseTypes = $this->warehouseType;
        if ($str = \request('search'))
        {
            $warehouseTypes = $warehouseTypes->search($str);
        }

        $warehouseTypes = $warehouseTypes->filter();
        $warehouseTypes = $warehouseTypes->sort()->paginate($this->per_page);

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'    => new \App\Http\Resources\WarehouseTypeCollection($warehouseTypes)
           ]
        ]);
    }

    public function inventory()
    {
        $warehouseTypes = $this->warehouseType;
        if ($str = \request('search'))
        {
            $warehouseTypes = $warehouseTypes->search($str);
        }

        $warehouseTypes = $warehouseTypes->filter();
        $warehouseTypes = $warehouseTypes->paginate($this->per_page);

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'   => new WarehouseTypeCollection($warehouseTypes)
           ],
        ]);
    }

    public function show(WarehouseType $warehouseType)
    {
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'warehouseType' => new \App\Http\Resources\WarehouseType($warehouseType)
                ]
            ]
        ]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:100',
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

        $warehouseType = WarehouseType::create($request->all());

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.store_success',['name' => __('messages.warehouse_type')]),
                'data'    => [
                    'warehouseType' => new \App\Http\Resources\WarehouseType($warehouseType)
                ]
            ]
        ]);
    }

    public function update(Request $request, WarehouseType $warehouseType)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:100',
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

        $warehouseType->update($request->all());

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.update_success',['name' => __('messages.warehouse_type')]),
                'data'    => [
                    'warehouseType' => new \App\Http\Resources\WarehouseType($warehouseType)
                ]
            ]
        ]);
    }

    public function destroy(WarehouseType $warehouseType)
    {
        try {
            $warehouseType->delete();
        } catch (\Exception $e) {

        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.destroy_success',['name' => __('messages.warehouse_type')]),
                'data'    => [
                ]
            ]
        ]);
    }
}
