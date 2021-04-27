<?php

namespace App\Http\Controllers\Api;

use App\EmployeeGroup;
use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Resources\EmployeeGroupCollection;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class EmployeeGroupController extends Controller
{
    protected $response;

    protected $per_page;

    protected $employeeGroup;

    protected $apiResponse;

    private $message_not_found;

    public function __construct(Response $response, ApiResponse $apiResponse, EmployeeGroup $employeeGroup)
    {
        $this->middleware('auth:api');

        $this->response = $response;
        $this->employeeGroup = $employeeGroup;
        $this->apiResponse = $apiResponse;
        $this->per_page = request('per_page') ? request('per_page') : 10000000;
        $this->message_not_found = trans('strings.not_found',['name' => __('messages.employeeGroup')]);
    }


    public function index()
    {
        $employeeGroups = $this->employeeGroup;

        if ($str = \request('search'))
        {
            $employeeGroups = $employeeGroups->search($str);
        }

        $employeeGroups = $employeeGroups->filter();
        $employeeGroups = $employeeGroups->sort()->paginate($this->per_page);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => new EmployeeGroupCollection($employeeGroups)
            ]
        ]);
    }


    public function show($id)
    {
        if (!$employeeGroup = $this->employeeGroup->find($id))
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
                    'employeeGroup' => new \App\Http\Resources\EmployeeGroup($employeeGroup)
                ]
            ]
        ]);
    }



    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required',
        ]);

        if ( $validator->fails() )
        {
            return $this->response->withArray([
                'result' => [ 'success'   => false, 'data' => []],
                'error' => [
                    'message'   => __('messages.validation_error'),
                    'code'      => ApiResponse::VALIDATION_ERROR,
                ],
                'validation_errors' => $validator->errors()
            ])->setStatusCode(ApiResponse::VALIDATION_ERROR);
        }

        $employeeGroup = EmployeeGroup::create($request->all());

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.store_success',['name' => __('messages.employeeGroup')]),
                'data'    => [
                    'employeeGroup' => new \App\Http\Resources\EmployeeGroup($employeeGroup)
                ]
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        if (!$employeeGroup = EmployeeGroup::find($id))
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
            'name'          => 'required|string|max:100',
        ]);

        if ( $validator->fails() )
        {
            return $this->response->withArray([
                'result' => [ 'success'   => false, 'data' => []],
                'error' => [
                    'message'   => __('messages.validation_error'),
                    'code'      => ApiResponse::VALIDATION_ERROR,
                    'validation_errors' => $validator->errors()
                ]
            ])->setStatusCode(ApiResponse::VALIDATION_ERROR);
        }

        $employeeGroup->update($request->all());

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.update_success',['name' => __('messages.employeeGroup')]),
                'data'    => [
                    'employeeGroup' => new \App\Http\Resources\EmployeeGroup($employeeGroup)
                ]
            ]
        ]);
    }


    public function destroy($id)
    {
        $employeeGroup = EmployeeGroup::find($id);

        if (is_null($employeeGroup))
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

        $employeeGroup->delete();

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.destroy_success',['name' => __('messages.employeeGroup')]),
                'data'    => [
                    'message' => trans('messages.successfully_deleted')
                ]
            ]
        ]);
    }
}
