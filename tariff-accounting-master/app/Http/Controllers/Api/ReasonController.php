<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReasonCollection;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Reason;

class ReasonController extends Controller
{
    protected $response;

    protected $per_page;

    protected $reason;

    protected $apiResponse;

    private $message_not_found;

    public function __construct(Response $response, ApiResponse $apiResponse, Reason $reason)
    {
        $this->middleware('auth:api');
        $this->response = $response;
        $this->reason = $reason;
        $this->apiResponse = $apiResponse;
        $this->per_page = request()->get('per_page',100000) ;
        $this->message_not_found = trans('messages.not_found',['name' => __('messages.reason')]);
    }

    public function index()
    {
        $reasons = $this->reason;
        if ($str = \request('search'))
        {
            $reasons = $reasons->search($str);
        }

        $reasons = $reasons->filter();
        $reasons = $reasons->sort()->paginate($this->per_page);

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'    => new ReasonCollection($reasons)
           ]
        ]);
    }

    public function show(Reason $reason)
    {
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'reason' => new \App\Http\Resources\Reason($reason)
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

        $reason = Reason::create($request->all());

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.store_success',['name' => __('messages.reason')]),
                'data'    => [
                    'reason' => new \App\Http\Resources\Reason($reason)
                ]
            ]
        ]);
    }


    public function update(Request $request, Reason $reason)
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

        $reason->update($request->all());

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.update_success',['name' => __('messages.reason')]),
                'data'    => [
                    'reason' => new \App\Http\Resources\Reason($reason)
                ]
            ]
        ]);
    }

    public function destroy(Reason $reason)
    {
        try {
            $reason->delete();
        } catch (\Exception $e) {
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.destroy_success',['name' => __('messages.reason')]),
                'data'    => [
                ]
            ]
        ]);
    }
}
