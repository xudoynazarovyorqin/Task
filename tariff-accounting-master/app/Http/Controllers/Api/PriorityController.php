<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Resources\PriorityCollection;
use App\Priority;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PriorityController extends Controller
{
    protected $response;

    protected $priority;

    protected $per_page;

    protected $apiResponse;

    private $message_not_found;

    public function __construct(Response $response, ApiResponse $apiResponse,Priority $priority)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:priority.create')->only('store');
        $this->middleware('permission:priority.show')->only('show');
        $this->middleware('permission:priority.update')->only('update');
        $this->middleware('permission:priority.delete')->only(['destroy']);

        $this->response = $response;
        $this->apiResponse = $apiResponse;
        $this->priority = $priority;
        $this->per_page = request()->get('per_page',1000000);
        $this->message_not_found = trans('messages.not_found',['name' => __('messages.priority')]);
    }

    public function index()
    {
        $list = $this->priority;

        if ($str = \request('search'))
        {
            $list = $list->search($str);
        }

        $list = $list->filter();
        $list = $list->sort()->paginate($this->per_page);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => new PriorityCollection($list)
            ]
        ]);
    }

    public function inventory(){

        $priority = $this->priority;

        if ($str = \request('search'))
        {
            $priority = $priority->search($str);
        }

        $priority = $priority->filter();
        $priority = $priority->sort()->paginate($this->per_page);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'  => new \App\Http\Resources\Inventory\PriorityCollection($priority)
            ]
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

        $priority = Priority::create([
            'name'    => $request['name'],
        ]);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.store_success',['name' => __('messages.priority')]),
                'data'    => [
                    'priority' => new \App\Http\Resources\Priority($priority)
                ],
            ]
        ]);
    }

    public function show($id)
    {
        if (!$priority = $this->priority->find($id))
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

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'priority' => new \App\Http\Resources\Priority($priority)
                ]
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        if (!$priority = $this->priority->find($id))
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

        $priority->update([
            'name'    => $request['name'],
        ]);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.update_success',['name' => __('messages.priority')]),
                'data'    => [
                    'priority' => new \App\Http\Resources\Priority($priority)
                ],
            ]
        ]);
    }


    public function destroy($id)
    {
        if (!$model = $this->priority->find($id))
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
                'message' => trans('messages.destroy_success',['name' => trans('messages.priority')]),
            ]
        ]);
    }
}
