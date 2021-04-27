<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Interfaces\PermissionInterface;
use App\Http\Requests\PermissionRequest;
use App\Http\Resources\PermissionCollection;
use App\Permission;
use EllipseSynergie\ApiResponse\Laravel\Response;

class PermissionController extends Controller implements PermissionInterface
{
    protected $response;

    protected $per_page;

    protected $permission;

    protected $apiResponse;

    private $message_not_found;

    public function __construct(Response $response, ApiResponse $apiResponse, Permission $permission)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:permissions.create')->only('store');
        $this->middleware('permission:permissions.show')->only('show');
        $this->middleware('permission:permissions.update')->only('update');
        $this->middleware('permission:permissions.delete')->only(['destroy']);

        $this->response = $response;
        $this->permission = $permission;
        $this->apiResponse = $apiResponse;
        $this->per_page = request()->get('per_page' , 1000000);
        $this->message_not_found = trans('strings.not_found',['name' => __('messages.permission')]);
    }

    public function index()
    {
        $permissions = $this->permission;
        if ($str = \request('search'))
        {
            $permissions = $permissions->search($str);
        }

        $permissions = $permissions->filter();
        $permissions = $permissions->with('children')->sort()->paginate($this->per_page);

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'    => new PermissionCollection($permissions)
           ]
        ]);
    }

    public function show(Permission $permission)
    {
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'permission'  => new \App\Http\Resources\Permission($permission)
                ]
            ]
        ]);
    }


    public function store(PermissionRequest $request)
    {
        $permission = Permission::create($request->all());

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.store_success',['name' => __('messages.permission')]),
                'data'    => [
                    'permission'  => $permission
                ]
            ]
        ]);
    }

    public function update(PermissionRequest $request, Permission $permission)
    {
        $permission->update($request->all());

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.update_success',['name' => __('messages.permission')]),
                'data'    => [
                    'permission'  => $permission
                ]
            ]
        ]);
    }

    public function destroy(Permission $permission)
    {
        try {
            $permission->delete();
        } catch (\Exception $e) {
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.destroy_success',['name' => __('messages.permission')]),
                'data'    => [
                    'message' => trans('messages.successfully_deleted')
                ]
            ]
        ]);
    }

    public function parents(){
        $permissions = Permission::where('parent_id',null)->with('children')->paginate(10000000);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'permissions'  => new PermissionCollection($permissions)
                ]
            ]
        ]);
    }
}
