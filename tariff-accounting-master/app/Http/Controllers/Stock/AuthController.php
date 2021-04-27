<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\ApiResponse\ApiResponse;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Permission;
use App\Http\Resources\Relation\Role;
use App\User;
use App\UserAuthLog;

class AuthController extends Controller
{

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var ApiResponse
     */
    protected $apiResponse;

    /**
     * AuthController constructor.
     * @param Response $response
     * @param ApiResponse $apiResponse
     */
    public function __construct(Response $response, ApiResponse $apiResponse)
    {
        $this->middleware('stock_token')->except(['login']);
        $this->response = $response;
        $this->apiResponse = $apiResponse;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pin_code' => 'required',
        ]);

        if ( $validator->fails() ) {
            return $this->response->withArray([
                'result' => [
                    'success'   => false,
                    'data'      => []
                ],
                'error' => [
                    'message'   => __('messages.validation_error'),
                    'code'      => ApiResponse::VALIDATION_ERROR,
                    'validation_errors' => $validator->errors()
                ]
            ])->setStatusCode(ApiResponse::VALIDATION_ERROR);
        }

        $user = User::with('role')->where('pin_code', '=', $request['pin_code'])->first();

        if( !$user )
        {
            return $this->response->withArray(
                [
                    'result' => ['success' => false],
                    'error' => [
                        'message' => __('messages.invalid_login_credentials'),
                        'code'    => ApiResponse::BAD_REQUEST
                    ]
                ])->setStatusCode(ApiResponse::BAD_REQUEST);
        }

        $token = bcrypt($request['pin_code'] + date('YmdHis'));

        $user->last_login = now();
        $user->access_token = $token;
        $user->save();

        UserAuthLog::create([
            'user_id'   => $user->id,
            'status'    => 1,
        ]);

        return $this->respondWithToken($token, $user);
    }

    /**
     * @param $token
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    protected function respondWithToken($token, $user)
    {
        return $this->response->withArray(
            [
                'result' => [
                    'success' => true,
                    'data'    => [
                        'token'     => $token,
                        'name'      => $user->name,
                        'role'      => new Role($user->role,true),
                    ]
                ]
            ]);
    }

    public function user(Request $request)
    {
        $access_token = $request->header('AccessToken');
        $user = User::with('role')->where('access_token',$access_token)->first();

        return $this->response->withArray(
            [
                'result' => [
                    'success' => true,
                    'data'    => [
                        'role'   => new Role($user->role,true),
                        'name'   => $user->name,
                        'number_money' => setting('number_money'),
                        'number_quantity' => setting('number_quantity')
                    ]
                ],
            ]);
    }

    public function logout(Request $request)
    {
        $access_token = $request->header('AccessToken');
        $user = User::where('access_token',$access_token)->first();

        $user->access_token = null;
        $user->save();

        UserAuthLog::create([
            'user_id'   => $user->id,
            'status'    => 0,
        ]);

        return $this->response->withArray(
            [
                'result' => [
                    'success' => true,
                    'data'  => [
                        'message' => __('messages.logout_success')
                        ]
                    ],
            ]);
    }
}
