<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Support\Facades\Log;

class StockTokenMiddleware
{
    protected $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function handle($request, Closure $next)
    {
        if (!$access_token = $request->header('AccessToken'))
        {
            return response()->json([
                'result' => [
                    'success' => false,
                    'data'    => []
                ],
                'error' => [
                    'message' => __('messages.token_invalid'),
                    'code'    => 401
                ]],
                401);
        }

        if (!$user = User::where('access_token',$access_token)->first())
        {
            return response()->json([
                'result' => [
                    'success' => false,
                    'data'    => []
                ],
                'error' => [
                    'message' => __('messages.token_invalid'),
                    'code'    => 401
                ]],
                401);
        }

        return $next($request);
    }
}
