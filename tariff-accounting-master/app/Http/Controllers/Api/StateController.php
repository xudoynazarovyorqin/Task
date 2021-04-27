<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Resources\Inventory\StateCollection;
use App\State;
use EllipseSynergie\ApiResponse\Laravel\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StateController extends Controller
{
    protected $response;

    protected $state;

    protected $per_page;

    protected $apiResponse;

    private $message_not_found;

    private $model;

    public function __construct(Response $response, ApiResponse $apiResponse,State $state)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:states.create')->only('store');
        $this->middleware('permission:states.show')->only('show');
        $this->middleware('permission:states.update')->only('update');
        $this->middleware('permission:states.delete')->only(['destroy']);

        $this->response = $response;
        $this->apiResponse = $apiResponse;
        $this->state = $state;
        $this->per_page = request()->get('per_page',1000000);
        $this->message_not_found = trans('messages.state_not_found');
        $this->model = $state;
    }

    public function index()
    {
        $states = $this->state;

        if ($str = \request('search'))
        {
            $states = $states->search($str);
        }

        $states = $states->filter();
        $states = $states->sort()->paginate($this->per_page);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => new \App\Http\Resources\StateCollection($states)
            ]
        ]);
    }

    public function inventory(){

        $states = $this->state;

        if ($str = \request('search'))
        {
            $states = $states->search($str);
        }

        $states = $states->filter();
        $states = $states->sort()->paginate($this->per_page);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'  =>  new StateCollection($states)
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'state' => 'required',
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

        $state = State::create([
            'state'    => $request['state'],
            'status'   => $request['status'],
        ]);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.store_success',['name' => __('messages.status')]),
                'data'    => [
                    'state'  => new \App\Http\Resources\State($state)
                ],
            ]
        ]);
    }

    public function show(State $state)
    {
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'state'  => new \App\Http\Resources\State($state)
                ]
            ]
        ]);
    }

    public function update(Request $request, State $state)
    {
        $validator = Validator::make($request->all(), [
            'state' => 'required',
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

        $state->update([
            'state'    => $request['state'],
            'status'   => $request['status'],
        ]);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.update_success',['name' => __('messages.status')]),
                'data'    => [
                    'state'  => new \App\Http\Resources\State($state)
                ],
            ]
        ]);
    }
    public function destroy(State $state)
    {
        try {
            $state->delete();
        } catch (\Exception $e) {

        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.destroy_success',['name' => trans('messages.status')]),
            ]
        ]);
    }


}
