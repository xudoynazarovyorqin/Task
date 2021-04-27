<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\ApiResponse\ApiResponse;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;
use App\Reason;

class ReasonController extends Controller
{
    /**
     * @var Response
     */
    protected $response;

    /**
     * @var ApiResponse
     */
    protected $apiResponse;

    protected $reason;

    private $message_not_found;

    /**
     * ReasonController constructor.
     * @param Response $response
     * @param ApiResponse $apiResponse
     */
    public function __construct(Response $response, ApiResponse $apiResponse, Reason $reason)
    {
        $this->middleware('stock_token');
        $this->response = $response;
        $this->apiResponse = $apiResponse;
        $this->reason = $reason;
        $this->message_not_found = trans('messages.not_found',['name' => trans('messages.reason')]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function index()
    {
        $reasons = $this->reason->get();

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'    => [
                   'reasons'  => $reasons
               ]
           ]
        ]);
    }
}
