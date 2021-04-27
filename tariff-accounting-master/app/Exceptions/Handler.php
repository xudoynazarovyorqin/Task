<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    public function render($request, Exception $exception)
    {
        if ($exception instanceof ApiModelNotFoundException){
            return response()->json([
                'result' => [
                    'success' => false,
                    'data'    => []
                ],
                'error' => [
                    'message' => $exception->getMessage(),
                    'code'    => 404
                ]],
                404);
        }

        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'result' => [
                    'success' => false,
                    'data'    => []
                ],
                'error' => [
                    'message' => __('messages.Object not found'),
                    'code'    => 404
                ]],
                404);
        }

        if ($exception instanceof AuthenticationException){
            return response()->json([
                'result' => [
                    'success' => false,
                    'data'    => []
                ],
                'error' => [
                    'message' => __('messages.unauthenticated'),
                    'code'    => 401
                ]],
                401);
        }elseif ($exception instanceof PermissionDeniedException){
            return response()->json([
                'result' => [
                    'success' => false,
                    'data'    => []
                ],
                'error' => [
                    'message' => __('messages.permission_denied'),
                    'code'    => 400
                ]],
                400);
        }
        return parent::render($request, $exception);
    }
}
