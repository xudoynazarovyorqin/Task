<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Interfaces\RoleInterface;
use App\Http\Requests\RoleRequest;
use App\Http\Resources\RoleCollection;
use App\Role;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller implements RoleInterface
{

    protected $response;

    protected $per_page;

    protected $role;

    protected $apiResponse;

    private $message_not_found;

    public function __construct(Response $response, ApiResponse $apiResponse, Role $role)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:roles.create')->only('store');
        $this->middleware('permission:roles.show')->only('show');
        $this->middleware('permission:roles.update')->only('update');
        $this->middleware('permission:roles.delete')->only(['destroy']);

        $this->response = $response;
        $this->role = $role;
        $this->apiResponse = $apiResponse;
        $this->per_page = request()->get('per_page',1000000);
        $this->message_not_found = trans('strings.not_found',['name' => __('messages.role')]);
    }

    public function index()
    {
        $roles = $this->role;
        if ($str = \request('search'))
        {
            $roles = $roles->search($str);
        }

        $roles = $roles->filter();
        $roles = $roles->sort()->paginate($this->per_page);

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'    => new RoleCollection($roles)
           ]
        ]);
    }

    public function inventory()
    {
        $roles = $this->role;
        if ($str = \request('search'))
        {
            $roles = $roles->search($str);
        }

        $roles = $roles->filter();
        $roles = $roles->sort()->paginate($this->per_page);

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'  => new \App\Http\Resources\Inventory\RoleCollection($roles)
           ],
        ]);
    }

    public function show(Role $role)
    {
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'role'  => new \App\Http\Resources\Role($role,true),
                ]
            ]
        ]);
    }

    public function store(RoleRequest $request)
    {
        $role = Role::create($request->all());

        if ($request['permissions'])
        {
            $permissions = $request['permissions'];
            if (is_array($permissions)){
                $role->permissions()->sync($permissions);
            }
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.store_success',['name' => __('messages.role')]),
                'data'    => [
                    'role'  => new \App\Http\Resources\Role($role)
                ]
            ]
        ]);
    }

    public function update(RoleRequest $request, Role $role)
    {
        $role->update($request->all());

        if ($permissions = $request['permissions'])
        {
            if (is_array($permissions)){
                $role->permissions()->sync($permissions);
            }
        }else{
            $role->permissions()->detach();
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.update_success',['name' => __('messages.role')]),
                'data'    => [
                    'role'  => new \App\Http\Resources\Role($role)
                ]
            ]
        ]);
    }

    public function destroy(Role $role)
    {
        try {
            $role->delete();
        } catch (\Exception $e) {
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.destroy_success',['name' => __('messages.role')]),
            ]
        ]);
    }
}
