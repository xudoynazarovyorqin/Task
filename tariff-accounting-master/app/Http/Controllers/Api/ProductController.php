<?php

namespace App\Http\Controllers\Api;

use App\Events\Copy\CopyProductEvent;
use App\Exceptions\ApiModelNotFoundException;
use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\Inventory\ProductCollection;
use App\Product;
use App\ProductMaterial;
use App\ProductSemiProduct;
use App\WarehouseProduct;
use EllipseSynergie\ApiResponse\Laravel\Response;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    protected $response;

    protected $product;

    protected $per_page;

    protected $apiResponse;

    private $message_not_found;

    public function __construct(Response $response, ApiResponse $apiResponse,Product $product)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:products.create')->only('store');
        $this->middleware('permission:products.show')->only('show');
        $this->middleware('permission:products.update')->only('update');
        $this->middleware('permission:products.delete')->only(['destroy']);

        $this->response = $response;
        $this->apiResponse = $apiResponse;
        $this->product = $product;
        $this->per_page = request()->get('per_page',1000000);
        $this->message_not_found = trans('messages.product_not_found');
    }

    public function index()
    {
        $products = $this->product;

        if ($str = \request('search'))
        {
            $products = $products->search($str);
        }

        $products = $products->filter();
        $products = $products->sort()->paginate($this->per_page);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data' => new \App\Http\Resources\ProductCollection($products)
            ],
        ]);
    }

    public function inventory()
    {
        $products = $this->product;

        if ($str = \request('search'))
        {
            $products = $products->search($str);
        }

        $products = $products->filter();
        $products = $products->sort()->paginate($this->per_page);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'  =>  new ProductCollection($products)
            ],
        ]);
    }

    public function store(ProductRequest $request)
    {
        $product = Product::create([
           'name'                   => $request->get('name',null),
           'purchase_price'         => $request->get('purchase_price',0.0),
           'purchase_currency_id'   => $request->get('purchase_currency_id',null),
           'selling_price'          => $request->get('selling_price',0.0),
           'selling_currency_id'    => $request->get('selling_currency_id',null),
           'measurement_id'         => $request->get('measurement_id',null),
           'description'            => $request->get('description',null),
           'code'                   => $request->get('code',null),
           'vendor_code'            => $request->get('vendor_code',null),
           'nds'                    => $request->get('nds',null),
           'country_id'             => $request->get('country_id',null),
           'warehouse_type_id'      => $request->get('warehouse_type_id',null),
           'recycled'               => $request->get('recycled',false),
           'production'             => $request->get('production',0) ? 1 : 0,
           'production_type'        => $request->get('production_type',null)
        ]);

        if ($product_materials = $request->get('product_materials',null)){
            if (is_array($product_materials)){
                foreach ($product_materials as $product_material){
                    $product->materials()->create($product_material);
                }
            }
        }

        if ($semi_products = $request->get('semi_products',null)){
            if (is_array($semi_products)){
                foreach ($semi_products as $semi_product){
                   $product->semi_products()->create($semi_product);
                }
            }
        }

        if ($categories = $request->get('categories',null)){
            if (is_array($categories)){
                $product->categories()->sync($categories);
            }
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.store_success',['name' => __('messages.product')]),
                'data' => [
                    'product' => new \App\Http\Resources\Product($product,true)
                ]
            ]
        ]);
    }

    public function show(Product $product)
    {
        if ($info = request()->get('warehouse_info',false))
            return $this->response->withArray([
                'result' => [
                    'success' => true,
                    'data'    => [
                        'product'   => new \App\Http\Resources\Product($product),
                        'available' => floatval($product->warehouse_products()->sum('remainder')),
                        'booked'    => floatval($product->warehouse_products()->sum('booked')),
                    ]
                ]
            ]);
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'product'  => new \App\Http\Resources\Product($product,true)
                ]
            ]
        ]);
    }

    public function update(ProductRequest $request, Product $product)
    {
        $product->update([
            'name'                   => $request->get('name',null),
            'purchase_price'         => $request->get('purchase_price',0.0),
            'purchase_currency_id'   => $request->get('purchase_currency_id',null),
            'selling_price'          => $request->get('selling_price',0.0),
            'selling_currency_id'    => $request->get('selling_currency_id',null),
            'measurement_id'         => $request->get('measurement_id',null),
            'description'            => $request->get('description',null),
            'code'                   => $request->get('code',null),
            'vendor_code'            => $request->get('vendor_code',null),
            'nds'                    => $request->get('nds',null),
            'country_id'             => $request->get('country_id',null),
            'warehouse_type_id'      => $request->get('warehouse_type_id',null),
            'recycled'               => $request->get('recycled',false),
            'production'             => $request->get('production',0) ? 1 : 0,
            'production_type'        => $request->get('production_type',null)
        ]);

        if ($categories = $request->get('categories',null)){
            if (is_array($categories)){
                $product->categories()->sync($categories);
            }
        }

        ProductMaterial::where('product_id',$product->id)->delete();
        ProductSemiProduct::where('product_id',$product->id)->delete();

        if ($product_materials = $request->get('product_materials',null)){
            if (is_array($product_materials)){
                foreach ($product_materials as $product_material){
                   $product->materials()->create($product_material);
                }
            }
        }

        if ($semi_products = $request->get('semi_products',null)){
            if (is_array($semi_products)){
                foreach ($semi_products as $semi_product){
                     $product->semi_products()->create($semi_product);
                }
            }
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.update_success',['name' => __('messages.product')]),
            ]
        ]);
    }

    public function destroy(Product $product)
    {
        try {
            $product->delete();
        } catch (\Exception $e) {

        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.destroy_success',['name' => __('messages.product')]),
            ]
        ]);
    }

    public function remainder ()
    {
        $qty = WarehouseProduct::where('product_id','=',\request()->get('id',null))->sum('remainder');
        return $this->response->withArray([
            'result' => [
                'success'   => true,
                'quantity'  => floatval($qty)
            ],
        ]);
    }

    public function copy(){

        if (!$model = $this->product::find(\request()->get('id',null))){
           throw new ApiModelNotFoundException();
        }

        $event = new CopyProductEvent($model);
        event($event);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.copy_success',['name' => __('messages.product')]),
            ],
        ]);
    }
}
