<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Resources\LevelCollection;
use App\Http\Resources\LevelResource;
use App\Level;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class LevelController extends Controller
{
    protected $response;

    protected $model;

    protected $per_page;

    protected $apiResponse;

    private $message_not_found;

    private $listIndex;

    private $modelIndex;

    public function __construct(Response $response, ApiResponse $apiResponse, Level $model)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:levels.create')->only('store');
        $this->middleware('permission:levels.show')->only('show');
        $this->middleware('permission:levels.update')->only('update');
        $this->middleware('permission:levels.delete')->only(['destroy']);

        $this->response = $response;
        $this->apiResponse = $apiResponse;
        $this->model = $model;
        $this->per_page = request()->get('per_page' , 1000000);
        $this->message_not_found = trans('messages.not_found',['name' => __('messages.level')]);
        $this->listIndex  = 'levels';
        $this->modelIndex  = 'level';
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
                'data'    => new LevelCollection($list)
            ]
        ]);
    }

    public function inventory()
    {
        $list = $this->model;

        if ($str = \request('search'))
        {
            $list = $list->search($str);
        }

        $list = $list->filter();
        $list = $list->sort()->get();

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    =>  new \App\Http\Resources\Inventory\LevelCollection($list)
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

        $model = $this->model::create($request->all());

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.store_success',['name' => __('messages.level')]),
                'data'    => [
                    $this->modelIndex => new \App\Http\Resources\Level($model)
                ],
            ]
        ]);
    }

    public function show(Level $level)
    {
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    $this->modelIndex  => new \App\Http\Resources\Level($level)
                ]
            ]
        ]);
    }

    public function update(Request $request, Level $level)
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

        $level->update($request->all());

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.update_success',['name' => __('messages.level')]),
                'data'    => [
                    $this->modelIndex => new \App\Http\Resources\Level($level)
                ],
            ]
        ]);
    }

    public function destroy(Level $level)
    {
        try {
            $level->delete();
        } catch (\Exception $e) {
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.destroy_success',['name' => __('messages.level')]),
            ]
        ]);
    }
}
