<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use EllipseSynergie\ApiResponse\Laravel\Response;
use App\Http\Resources\WarehouseProductResource;
use App\WarehouseProduct;

class SaleProducedWarehouseController extends Controller
{
    /**
     * @var Response
     */
    protected $response;

    protected $per_page;
    protected $producedProducts;
    /**
     * @var ApiResponse
     */
    protected $apiResponse;
    private $message_not_found;

    public function __construct(Response $response, ApiResponse $apiResponse, WarehouseProduct $producedProducts)
    {
        $this->middleware('auth:api');
        $this->response = $response;
        $this->producedProducts = $producedProducts;
        $this->apiResponse = $apiResponse;
        $this->per_page = request('per_page') ? request('per_page') : 1000000;
        $this->message_not_found = trans('messages.not_found',['name' => trans('messages.sale_produced_warehouse')]);
    }

    public function index()
    {
        $producedProducts = $this->producedProducts->where('warehouse_productable_type', 'sale_products')->where('owner', WarehouseProduct::CLIENT);
        if ($str = \request('search'))
        {
            $producedProducts = $producedProducts->search($str);
        }

        $producedProducts = $producedProducts->filter();
        $producedProducts = $producedProducts->sort()->paginate($this->per_page);

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'    => [
                   'saleProducedWarehouses'  => new WarehouseProductResource($producedProducts)
               ]
           ]
        ]);
    }
}
