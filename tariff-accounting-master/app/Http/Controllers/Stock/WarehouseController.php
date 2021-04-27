<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\ApiResponse\ApiResponse;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;
use App\Warehouse;

class WarehouseController extends Controller
{
    /**
     * @var Response
     */
    protected $response;

    /**
     * @var ApiResponse
     */
    protected $apiResponse;

    protected $warehouse;

    private $message_not_found;

    /**
     * WarehouseController constructor.
     * @param Response $response
     * @param ApiResponse $apiResponse
     */
    public function __construct(Response $response, ApiResponse $apiResponse, Warehouse $warehouse)
    {
        $this->middleware('stock_token');
        $this->response = $response;
        $this->apiResponse = $apiResponse;
        $this->warehouse = $warehouse;
        $this->message_not_found = trans('messages.not_found',['name' => trans('messages.warehouse')]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function index()
    {
        $warehouses = $this->warehouse->get();

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'    => [
                   'warehouses'  => $warehouses
               ]
           ]
        ]);
    }
}
