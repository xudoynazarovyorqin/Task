<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\ApiResponse\ApiResponse;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;
use App\Assembly;
use App\User;
use App\WarehouseProduct;
use App\DefectProduct;
use App\DefectProductReason;
use App\OutputMaterial;
use App\OutputProduct;
use App\Http\Resources\Stock\AssemblyResource;

class AssemblyController extends Controller
{
    /**
     * @var Response
     */
    protected $response;

    /**
     * @var ApiResponse
     */
    protected $apiResponse;

    protected $assembly;

    private $message_not_found;

    /**
     * AssemblyController constructor.
     * @param Response $response
     * @param ApiResponse $apiResponse
     */
    public function __construct(Response $response, ApiResponse $apiResponse, Assembly $assembly)
    {
        $this->middleware('stock_token');
        $this->response = $response;
        $this->apiResponse = $apiResponse;
        $this->assembly = $assembly;
        $this->message_not_found = trans('messages.not_found',['name' => trans('messages.assembly')]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function index()
    {
        $access_token = request()->header('AccessToken');
        $user = User::where('access_token',$access_token)->first();
        $user_id = $user->id;        

        if( request('assembly_id') && is_numeric(request('assembly_id')) )
        {
            $assemblies = Assembly::whereHas('users_with_employee_group', function (Builder $query) use ($user_id) {
                $query->where('user_id', '=', $user_id);
            })->where('id', '=', request('assembly_id'))->get();  
        }
        else
        {            
            $assemblies = Assembly::whereHas('users_with_employee_group', function (Builder $query) use ($user_id) {
                $query->where('user_id', '=', $user_id);
            })->get();            
        }


        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'    => [
                   'assemblies'  => new AssemblyResource($assemblies)
               ]
           ]
        ]);
    }

    public function show($id)
    {
        $access_token = request()->header('AccessToken');
        $user = User::where('access_token',$access_token)->first();
        $user_id = $user->id;        

        $assembly = Assembly::whereHas('users_with_employee_group', function (Builder $query) use ($user_id) {
            $query->where('user_id', '=', $user_id);
        })->find($id);        

        if (!$assembly)
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

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'assembly' => new \App\Http\Resources\Stock\Assembly($assembly, true)
                ]
            ]
        ]);
    }

    public function assemblyProducedWarehouse(Request $request)
    {
        if ( !$assembly = $this->assembly->find($request['assembly_id']) ){
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

        if ($assembly_items = $request['assembly_items']) 
        {
            if (is_array($assembly_items)) 
            {
                if( $assembly->owner == \App\Sale::FOR_CLIENT )
                {
                    foreach ($assembly_items as $assembly_item)
                    {
                        if( $assembly_item['quantity'] > 0 )
                        {
                            $warehouseProduct = WarehouseProduct::create([
                               'warehouse_productable_id' => $assembly_item['assembly_item_id'],
                               'warehouse_productable_type' => 'assembly_items',
                               'agentable_id'        => ($assembly->assemblyable) ? $assembly->assemblyable->client_id : null,
                               'agentable_type'      => 'clients',
                               'product_id'          => $assembly_item['product_id'],
                               'qty_weight'          => null,
                               'remainder'           => $assembly_item['quantity'],
                               'receive'             => $assembly_item['quantity'],
                               'buy_price'           => 0,
                               'selling_price'       => $assembly_item['price'],
                               'warehouse_id'        => $assembly_item['warehouse_id'],
                               'owner'               => WarehouseProduct::CLIENT,
                            ]);
                        }
                    }            
                }
                else
                {
                    foreach ($assembly_items as $assembly_item)
                    {
                        if( $assembly_item['quantity'] > 0 )
                        {
                            $warehouseProduct = WarehouseProduct::create([
                               'warehouse_productable_id' => $assembly_item['assembly_item_id'],
                               'warehouse_productable_type' => 'assembly_items',
                               'agentable_id'        => null,
                               'agentable_type'      => null,
                               'product_id'          => $assembly_item['product_id'],
                               'qty_weight'          => null,
                               'remainder'           => $assembly_item['quantity'],
                               'receive'             => $assembly_item['quantity'],
                               'buy_price'           => 0,
                               'selling_price'       => $assembly_item['price'],
                               'warehouse_id'        => $assembly_item['warehouse_id'],
                               'owner'               => WarehouseProduct::FIRM,
                            ]);
                        }
                    }   
                }
            }
        }

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'message' => trans('messages.product_succes_added_to_warehouse'),
               'data'    => [
                   'assembly' => new \App\Http\Resources\Stock\Assembly($assembly, true)
               ]
           ]
        ]);
    }

    public function assemblyDefectCreate(Request $request)
    {
        if ( !$assembly = $this->assembly->find($request['assembly_id']) ){
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

        if ($defects = $request['defect_products']) 
        {
            if (is_array($defects))
            {
                foreach ($defects as $key => $defect)
                {
                    if( $defect['quantity'] > 0 )
                    {
                        $defect_product = DefectProduct::create(
                            [
                                'defectable_id'     => $defect['assembly_item_id'],
                                'defectable_type'   => 'assembly_items',
                                'product_id'        => $defect['product_id'],
                                'quantity'          => $defect['quantity'],
                                'date'              => $defect['date'],
                            ]
                        );
                        if (isset($defect['reasons']))
                        {
                            if (is_array($defect['reasons']))
                            {
                                foreach ($defect['reasons'] as $reason)
                                {
                                    DefectProductReason::create([
                                        'defect_product_id' => $defect_product->id,
                                        'reason_id'         => $reason['reason_id'],
                                        'quantity'          => $reason['quantity']
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.store_success',['name' => __('messages.product')]),
                'data'    => [
                    'assembly' => new \App\Http\Resources\Stock\Assembly($assembly, true)
                ],
            ]
        ]);
    }

    /*
    * Get assembly report (Norma rasxod)
    */
    public function report(Request $request)
    {
        $report_materials_with_price = [];
        $report_products_with_price = [];

        $assembly_id = $request['assembly_id'];        

        if ( $assembly = Assembly::find($assembly_id) )
        {
            $output_materials = OutputMaterial::where('output_materialable_id',$assembly->id)->where('output_materialable_type','assemblies')->get();
            $output_products = OutputProduct::where('output_productable_id',$assembly->id)->where('output_productable_type','assemblies')->get();
            /*
             * Get materials info from output materials
             */
            if (count($output_materials) > 0){
                foreach ($output_materials as $output_material){
                    array_push($report_materials_with_price,[
                        'material'           => new \App\Http\Resources\Relation\Material($output_material->material),
                        'warehouse'          => $output_material->warehouse_material ? new \App\Http\Resources\Warehouse($output_material->warehouse_material->warehouse) : null,
                        'quantity'           => $output_material->quantity,
                        'price'              => $output_material->warehouse_material ? $output_material->warehouse_material->price : 0,
                        'total_amount'       => $output_material->warehouse_material ? $output_material->quantity * $output_material->warehouse_material->price : 0,
                    ]);
                }
            }
            /*
             * Get products info from output products
             */
            if (count($output_products) > 0){
                foreach ($output_products as $output_product){
                    array_push($report_products_with_price,[
                        'product'            => new \App\Http\Resources\Product($output_product->product),
                        'warehouse'          => $output_product->warehouse_product ? new \App\Http\Resources\Warehouse($output_product->warehouse_product->warehouse) : null,
                        'quantity'           => $output_product->quantity,
                        'price'              => $output_product->warehouse_product ? $output_product->warehouse_product->buy_price : 0,
                        'total_amount'       => $output_product->warehouse_product ? $output_product->quantity * $output_product->warehouse_product->buy_price : 0,
                    ]);
                }
            }
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data' => [
                    'report_materials' => $report_materials_with_price,
                    'report_products' => $report_products_with_price,
                ]
            ]
        ]);
    }
    

    public function printReport()
    {
        if (!$assembly = $this->assembly->with('assemblyable.client')->find(\request('assembly_id'))){
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

        if( $assembly->owner == \App\Sale::FOR_FIRM )
        {
            $client_name = "Фирма";
        }
        else
        {
            if( $assembly->assemblyable )
            {
                if( $assembly->assemblyable->client )
                {
                    $client_name = $assembly->assemblyable->client->name;
                }
                else
                {
                    $client_name = '';
                }
            }
            else
            {
                $client_name = '';
            }
        }

        $assembly_material_warehouses = [];
        $assembly_product_warehouses = [];

        $output_materials = OutputMaterial::where('output_materialable_id',$assembly->id)->where('output_materialable_type','assemblies')->get();
        $output_products = OutputProduct::where('output_productable_id',$assembly->id)->where('output_productable_type','assemblies')->get();
        /*
         * Get materials info from output materials
         */
        if (count($output_materials) > 0){
            foreach ($output_materials as $output_material){
                array_push($assembly_material_warehouses,[
                    'material'           => new \App\Http\Resources\Relation\Material($output_material->material),
                    'warehouse'          => $output_material->warehouse_material ? new \App\Http\Resources\Warehouse($output_material->warehouse_material->warehouse) : null,
                    'quantity'           => $output_material->quantity,
                    'price'              => $output_material->warehouse_material ? $output_material->warehouse_material->price : 0,
                    'total_amount'       => $output_material->warehouse_material ? $output_material->quantity * $output_material->warehouse_material->price : 0,
                ]);
            }
        }
        /*
         * Get products info from output products
         */
        if (count($output_products) > 0){
            foreach ($output_products as $output_product){
                array_push($assembly_product_warehouses,[
                    'product'            => new \App\Http\Resources\Product($output_product->product),
                    'warehouse'          => $output_product->warehouse_product ? new \App\Http\Resources\Warehouse($output_product->warehouse_product->warehouse) : null,
                    'quantity'           => $output_product->quantity,
                    'price'              => $output_product->warehouse_product ? $output_product->warehouse_product->buy_price : 0,
                    'total_amount'       => $output_product->warehouse_product ? $output_product->quantity * $output_product->warehouse_product->buy_price : 0,
                ]);
            }
        }

        return view('assembly_output_warehouse_invoice',compact('assembly_material_warehouses', 'assembly_product_warehouses','assembly', 'client_name'));
    }

    public function printBarcode(Request $request)
    {
        $access_token = $request->header('AccessToken');
        $user = User::with('role')->where('access_token',$access_token)->first();

        if (!$assembly = $this->assembly->find(\request('assembly_id'))){
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

        return view('assembly_barcode',['assembly' => $assembly, 'user' => $user]);
    }
}
