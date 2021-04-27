<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserAuthLogCollection;
use App\UserAuthLog;
use EllipseSynergie\ApiResponse\Laravel\Response;

class UserAuthLogController extends Controller
{
    protected $response;

    protected $per_page;

    protected $userAuthLog;

    protected $apiResponse;

    private $message_not_found;

    public function __construct(Response $response, ApiResponse $apiResponse, UserAuthLog $userAuthLog)
    {
        $this->middleware('auth:api');
        $this->response = $response;
        $this->userAuthLog = $userAuthLog;
        $this->apiResponse = $apiResponse;
        $this->per_page = request()->get('per_page',1000000);
        $this->message_not_found = trans('messages.not_found',['name' => __('messages.userAuthLog')]);
    }

    public function index()
    {
        $userAuthLogs = $this->userAuthLog;
        if ($str = \request('search'))
        {
            $userAuthLogs = $userAuthLogs->search($str);
        }

        $userAuthLogs = $userAuthLogs->filter();
        $userAuthLogs = $userAuthLogs->sort()->paginate($this->per_page);

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'    => new UserAuthLogCollection($userAuthLogs)
           ]
        ]);
    }


    public function destroy($id)
    {
        $userAuthLog = UserAuthLog::find($id);

        if (is_null($userAuthLog))
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

        $userAuthLog->delete();

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.destroy_success',['name' => __('messages.userAuthLog')]),
                'data'    => [
                ]
            ]
        ]);
    }
}
