<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Resources\Inventory\MeasurementCollection;
use App\Measurement;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MeasurementController extends Controller
{
    protected $response;

    protected $per_page;

    protected $measurement;

    protected $apiResponse;

    private $message_not_found;

    public function __construct(Response $response, ApiResponse $apiResponse, Measurement $measurement)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:products.create')->only('store');
        $this->middleware('permission:products.show')->only('show');
        $this->middleware('permission:products.update')->only('update');
        $this->middleware('permission:products.delete')->only(['destroy']);

        $this->response = $response;
        $this->measurement = $measurement;
        $this->apiResponse = $apiResponse;
        $this->per_page = request('per_page') ? request('per_page') : 100000000;
        $this->message_not_found = trans('messages.not_found',['name' => __('messages.measurement')]);
    }

    public function index()
    {
        $measurements = $this->measurement;

        if ($str = \request('search'))
        {
            $measurements = $measurements->search($str);
        }

        $measurements = $measurements->filter();
        $measurements = $measurements->sort()->paginate($this->per_page);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => new \App\Http\Resources\MeasurementCollection($measurements)
            ]
        ]);

    }

    public function inventory()
    {
        $measurements = $this->measurement;

        if ($str = \request('search'))
        {
            $measurements = $measurements->search($str);
        }

        $measurements = $measurements->filter();
        $measurements = $measurements->sort()->paginate($this->per_page);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'  =>  new MeasurementCollection($measurements)
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
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

        $measurement = Measurement::create($request->all());

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.store_success',['name' => __('messages.measurement')]),
                'data'    => [
                    'measurement' => new \App\Http\Resources\Measurement($measurement)
                ],
            ]
        ]);
    }

    public function show(Measurement $measurement)
    {
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'measurement' => new \App\Http\Resources\Measurement($measurement)
                ],
            ]
        ]);
    }

    public function update(Request $request, Measurement $measurement)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
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

        $measurement->update($request->all());

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.update_success',['name' => __('messages.measurement')]),
                'data'    => [
                    'measurement' => new \App\Http\Resources\Measurement($measurement)
                ],
            ]
        ]);
    }

    public function destroy(Measurement $measurement)
    {
        try {
            $measurement->delete();
        } catch (\Exception $e) {

        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.destroy_success',['name' => __('messages.measurement')]),
                'data'    => [
                ]
            ]
        ]);
    }
}
