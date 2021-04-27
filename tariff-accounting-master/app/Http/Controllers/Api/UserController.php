<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Interfaces\UserInterface;
use App\Http\Requests\UserRequest;
use App\Http\Resources\Inventory\UserCollection;
use App\User;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected $response;

    protected $user;

    protected $per_page;

    protected $apiResponse;

    private $message_not_found;

    public function __construct(Response $response, ApiResponse $apiResponse,User $user)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:users.create')->only('store');
        $this->middleware('permission:users.show')->only('show');
        $this->middleware('permission:users.update')->only('update');
        $this->middleware('permission:users.delete')->only(['destroy']);


        $this->response = $response;
        $this->apiResponse = $apiResponse;
        $this->user = $user;
        $this->per_page = request()->get('per_page',1000000);
        $this->message_not_found = trans('messages.user_not_found');
    }

    public function index()
    {
        $users = $this->user;
        if ($str = \request('search'))
        {
            $users = $users->search($str);
        }

        $users = $users->filter();
        $users = $users->sort()->paginate($this->per_page);

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'    => new \App\Http\Resources\UserCollection($users)
           ]
        ]);
    }

    public function inventory()
    {
        $users = $this->user;
        if ($str = \request('search'))
        {
            $users = $users->search($str);
        }

        $users = $users->filter();
        $users = $users->sort()->paginate($this->per_page);

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'  =>  new UserCollection($users)
           ],
        ]);
    }

    public function show(User $user)
    {
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'user'  => new \App\Http\Resources\User($user)
                ]
            ]
        ]);
    }

    public function store(UserRequest $request)
    {
        $request['password'] = bcrypt($request['password']);
        $user = User::create($request->all());

        if( $request['is_employee'] )
        {
            if ($employee_groups = $request['employee_groups']){
                if (is_array($employee_groups)){
                    $user->employee_groups()->sync($employee_groups);
                }
            }
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.store_success',['name' => __('messages.user')]),
                'data'    => [
                    'user' => new \App\Http\Resources\User($user)
                ],
            ]
        ]);
    }

    public function validation(Request $request){
        $validator = null;

        if (isset($request['phone'])){
            $validator = Validator::make($request->all(), [
                'phone' => 'required|min:13|max:13|unique:users,phone',
            ]);
        }

        if (isset($request['pin_code'])){
            $validator = Validator::make($request->all(), [
                'pin_code' => 'required|min:6|max:6|unique:users,pin_code',
            ]);
        }

        if ($validator != null && $validator->fails()) {
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

        return $this->response->withArray(
            [
                'result' => [
                    'success' => true,
                    'data'     => []
                ],
            ]);
    }

    public function update(UserRequest $request, User $user)
    {
        $user->update([
            'name'          => $request['name'],
            'phone'         => $request['phone'],
            'first_name'    => $request['first_name'],
            'surname'       => $request['surname'],
            'patronymic'    => $request['patronymic'],
            'email'         => $request['email'],
            'role_id'       => $request['role_id'],
            'status'        => $request['status'],
            'is_employee'   => $request['is_employee'],
            'pin_code'      => $request['pin_code'],
        ]);

        if($request['password'] != ''){
            $user->password = bcrypt($request['password']);
            $user->update();
        }

        if( $request['is_employee'] )
        {
            if ($employee_groups = $request['employee_groups']){
                if (is_array($employee_groups)){
                    $user->employee_groups()->sync($employee_groups);
                }
            }
            else
            {
                $user->employee_groups()->detach();
            }
        }
        else
        {
            $user->employee_groups()->detach();
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.update_success',['name' => __('messages.user')]),
                'data'    => [
                    'user' => new \App\Http\Resources\User($user)
                ],
            ]
        ]);
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
        } catch (\Exception $e) {

        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.destroy_success',['name' => trans('messages.user')]),
            ]
        ]);
    }
}
