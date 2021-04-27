<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\CostCollection;
use App\Cost;
use App\Transaction;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CostController extends Controller
{
    protected $response;

    protected $per_page;

    protected $cost;

    protected $apiResponse;

    private $message_not_found;

    public function __construct(Response $response, ApiResponse $apiResponse, Cost $cost)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:costs.create')->only('store');
        $this->middleware('permission:costs.show')->only('show');
        $this->middleware('permission:costs.update')->only('update');
        $this->middleware('permission:costs.delete')->only(['destroy']);

        $this->response = $response;
        $this->cost = $cost;
        $this->apiResponse = $apiResponse;
        $this->per_page = request()->get('per_page',1000000);
        $this->message_not_found = trans('messages.not_found',['name' => __('messages.cost')]);
    }

    public function index()
    {
        $costs = $this->cost;
        if ($str = \request('search'))
        {
            $costs = $costs->search($str);
        }

        $costs = $costs->filter();
        $costs = $costs->sort()->paginate($this->per_page);

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'    => new CostCollection($costs)
           ]
        ]);
    }


    public function inventory()
    {
        $costs = $this->cost;
        if ($str = \request('search'))
        {
            $costs = $costs->search($str);
        }

        $costs = $costs->filter();
        $costs = $costs->sort()->paginate($this->per_page);

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'  => new \App\Http\Resources\Inventory\CostCollection($costs)
           ],
        ]);
    }

    public function show(Cost $cost)
    {
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'cost' => new \App\Http\Resources\Cost($cost)
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

        $cost = Cost::create($request->all());

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.store_success',['name' => __('messages.cost')]),
                'data'    => [
                    'cost' => new \App\Http\Resources\Cost($cost)
                ]
            ]
        ]);
    }


    public function update(Request $request, Cost $cost)
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

        $cost->update($request->all());

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.update_success',['name' => __('messages.cost')]),
                'data'    => [
                    'cost' => new \App\Http\Resources\Cost($cost)
                ]
            ]
        ]);
    }

    public function destroy(Cost $cost)
    {
        try {
            $cost->delete();
        } catch (\Exception $e) {
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.destroy_success',['name' => __('messages.cost')]),
                'data'    => [
                ]
            ]
        ]);
    }
    public function chart(){
        if (!$type = \request('type')){
            return response()->json([
                'result'  => [
                    'success' => false,
                ],
                'error' => [
                    'message' => __('messages.not_found',['name' => __('messages.diagram_type')])
                ]
            ]);
        }

        // TODO:: change filter
        $data = [];
        switch (\request('type')){
            case self::WEEKLY:
                $data  = ChartController::weekly(Transaction::class,'real_amount','costs'); break;
            case self::MONTHLY:
                $data  = ChartController::monthly(Transaction::class,'real_amount','costs'); break;
            case self::YEARLY:
                $data  = ChartController::yearly(Transaction::class,'real_amount','costs'); break;
            case self::PERIOD:
                $data  = ChartController::period(Transaction::class, \request('from_date'), \request('to_date'), 'real_amount','costs'); break;
        }

        return response()->json([
            'result' => [
                'success' => true,
                'data' => [
                    'chart_data' => $data
                ]
            ],
        ]);
    }
}
