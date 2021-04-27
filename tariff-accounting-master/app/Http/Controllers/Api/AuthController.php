<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Resources\Relation\Role;
use App\UserAuthLog;
use Carbon\Carbon;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected $response;

    protected $apiResponse;

    public function __construct(Response $response, ApiResponse $apiResponse)
    {
        $this->middleware('auth:api')->except(['login']);
        $this->response = $response;
        $this->apiResponse = $apiResponse;
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|min:13|max:13',
            'password' => 'required',
        ]);

        if ($validator->fails() || !ctype_digit(substr($request['phone'],1,strlen($request['phone']) - 1))) {
            return $this->response->withArray([
                'result' => [ 'success'   => false, 'data'      => []],
                'error' => [
                    'message'   => __('messages.validation_error'),
                    'code'      => ApiResponse::VALIDATION_ERROR,
                    'validation_errors' => $validator->errors()
                ]
            ])->setStatusCode(ApiResponse::VALIDATION_ERROR);
        }

        $credentials = $request->all();
        $user = null;

        if (!$token = auth()->attempt($credentials)) {
            return $this->response->withArray(
                [
                    'result' => ['success' => false],
                    'error' => [
                        'message' => __('messages.invalid_login_credentials'),
                        'code'    => ApiResponse::BAD_REQUEST
                    ]
                ])->setStatusCode(ApiResponse::BAD_REQUEST);
        }

        /**
         * Get user personal access token
         */
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addDay();
        $token->save();
        /**
         * Set user last login date.
         */
        $user->last_login = now();
        $user->save();

        UserAuthLog::create([
            'user_id'   => auth()->user()->id,
            'status'    => 1,
        ]);

        return $this->response->withArray(
            [
                'result' => [
                    'success' => true,
                    'data'    => [
                        'token'  => $tokenResult->accessToken,
                        "token_type"    => "Bearer ",
                        'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
                    ]
                ]
            ]);
    }

    public function user()
    {
        $user = auth()->user();
        return $this->response->withArray(
            [
                'result' => [
                    'success' => true,
                    'data'    => [
                        'role'   => new Role($user->role,true),
                        'name'   => $user->name,
                        'phone'  => $user->phone,
                        'number_money_product' => setting('number_money_product'),
                        'number_money_material' => setting('number_money_material'),
                        'number_quantity_product' => setting('number_quantity_product'),
                        'number_quantity_material' => setting('number_quantity_material'),
                    ]
                ],
            ]);
    }

    public function logout()
    {
        UserAuthLog::create([
            'user_id'   => auth()->user()->id,
            'status'    => 0,
        ]);

        $token = auth()->user()->token();
        $token->revoke();

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
