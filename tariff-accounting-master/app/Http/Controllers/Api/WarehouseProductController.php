<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ApiModelNotFoundException;
use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\WarehouseProductCollection;
use App\Http\Resources\WarehouseReportCollection;
use App\WarehouseProduct;
use App\Product;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Http\Request;

class WarehouseProductController extends Controller
{
    protected $response;

    protected $per_page;

    protected $warehouseProduct;

    protected $apiResponse;

    private $message_not_found;

    public function __construct(Response $response, ApiResponse $apiResponse, WarehouseProduct $warehouseProduct)
    {
        $this->middleware('auth:api');

        $this->response = $response;
        $this->warehouseProduct = $warehouseProduct;
        $this->apiResponse = $apiResponse;
        $this->per_page = request()->get('per_page',1000000);
        $this->message_not_found = trans('strings.warehouse_product_not_found');
    }

    public function index()
    {
        if ($str = \request('search'))
        {
            $products = Product::search($str)->filter();
        }
        else {
            $products = Product::filter();
        }

        $products = $products->whereHas('warehouse_products',function ($query){
            return $query->where([
                [ 'remainder', '>', 0],
                ['warehouse_id',\request()->get('warehouse_id',null)]
            ]);
        })->paginate($this->per_page);

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'    => new WarehouseReportCollection($products)
           ]
        ]);
    }

    public function comingProducts(Request $request)
    {
        if (!$product = Product::find($request['product_id'])){
            throw new ApiModelNotFoundException(trans('messages.not_found',['name' => trans('messages.product')]));
        }

        $comingProducts = WarehouseProduct::filter()->where([
            ['warehouse_id', '=',\request()->get('warehouse_id',null)],
            ['product_id', '=',$product->id],
            ['remainder', '>', 0]
        ])->get();

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'    => [
                   'warehouse_products'  => new WarehouseProductCollection($comingProducts)
               ]
           ]
        ]);
    }
}
