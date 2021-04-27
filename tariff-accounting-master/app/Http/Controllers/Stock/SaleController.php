<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\ApiResponse\ApiResponse;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;
use App\Sale;
use App\User;
use App\WarehouseProduct;
use App\DefectProduct;
use App\DefectProductReason;
use App\OutputMaterial;
use App\Http\Resources\Stock\SaleResource;

use Mike42\Escpos\Printer;
use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\PrintConnectors\CupsPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintBuffers\ImagePrintBuffer;
use Mike42\Escpos\GdEscposImage;

class SaleController extends Controller
{

    protected $response;

    /**
     * @var ApiResponse
     */
    protected $apiResponse;

    protected $sale;

    private $message_not_found;

    /**
     * SaleController constructor.
     * @param Response $response
     * @param ApiResponse $apiResponse
     */
    public function __construct(Response $response, ApiResponse $apiResponse, Sale $sale)
    {
        $this->middleware('stock_token');
        $this->response = $response;
        $this->apiResponse = $apiResponse;
        $this->sale = $sale;
        $this->message_not_found = trans('messages.not_found',['name' => trans('messages.sale')]);
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

        if( request('sale_id') && is_numeric(request('sale_id')) )
        {
            $sales = Sale::whereHas('users_with_employee_group', function (Builder $query) use ($user_id) {
                $query->where('user_id', '=', $user_id);
            })->where('id', '=', request('sale_id'))->get();
        }
        else
        {
            $sales = Sale::whereHas('users_with_employee_group', function (Builder $query) use ($user_id) {
                $query->where('user_id', '=', $user_id);
            })->get();
        }

        foreach ($sales as $sale) {
            $sale_product_ids = $sale->products->pluck('id');
            $old_warehouse_products = WarehouseProduct::with('product.measurement', 'warehouse')->where('warehouse_productable_type', 'sale_products')->whereIn('warehouse_productable_id', $sale_product_ids)->get();
            $sale->warehouse_products = $old_warehouse_products;
        }

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'    => [
                   'sales'  => new SaleResource($sales)
               ]
           ]
        ]);
    }

    public function show($id)
    {
        $access_token = request()->header('AccessToken');
        $user = User::where('access_token',$access_token)->first();
        $user_id = $user->id;

        $sale = Sale::whereHas('users_with_employee_group', function (Builder $query) use ($user_id) {
            $query->where('user_id', '=', $user_id);
        })->find($id);

        if (!$sale)
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
                    'sale' => new \App\Http\Resources\Stock\Sale($sale, true)
                ]
            ]
        ]);
    }

    public function saleProducedWarehouse(Request $request)
    {
        if ( !$sale = $this->sale->find($request['sale_id']) ){
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

        if ($sale_products = $request['sale_products'])
        {
            if (is_array($sale_products))
            {
                if( $sale->owner == Sale::FOR_CLIENT )
                {
                    foreach ($sale_products as $sale_product)
                    {
                        if( $sale_product['quantity'] > 0 )
                        {
                            $warehouseProduct = WarehouseProduct::create([
                               'warehouse_productable_id' => $sale_product['sale_product_id'],
                               'warehouse_productable_type' => 'sale_products',
                               'agentable_id'        => ($sale->saleable) ? $sale->saleable->client_id : null,
                               'agentable_type'      => 'clients',
                               'product_id'          => $sale_product['product_id'],
                               'qty_weight'          => null,
                               'remainder'           => $sale_product['quantity'],
                               'receive'             => $sale_product['quantity'],
                               'buy_price'           => 0,
                               'selling_price'       => $sale_product['price'],
                               'warehouse_id'        => $sale_product['warehouse_id'],
                               'owner'               => WarehouseProduct::CLIENT,
                            ]);
                        }
                    }
                }
                else
                {
                    foreach ($sale_products as $sale_product)
                    {
                        if( $sale_product['quantity'] > 0 )
                        {
                            $warehouseProduct = WarehouseProduct::create([
                               'warehouse_productable_id' => $sale_product['sale_product_id'],
                               'warehouse_productable_type' => 'sale_products',
                               'agentable_id'        => null,
                               'agentable_type'      => null,
                               'product_id'          => $sale_product['product_id'],
                               'qty_weight'          => null,
                               'remainder'           => $sale_product['quantity'],
                               'receive'             => $sale_product['quantity'],
                               'buy_price'           => 0,
                               'selling_price'       => $sale_product['price'],
                               'warehouse_id'        => $sale_product['warehouse_id'],
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
                   'sale' => new \App\Http\Resources\Stock\Sale($sale, true)
               ]
           ]
        ]);
    }

    public function saleDefectCreate(Request $request)
    {
        if ( !$sale = $this->sale->find($request['sale_id']) ){
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
                                'defectable_id'     => $defect['sale_product_id'],
                                'defectable_type'   => 'sale_products',
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
                    'sale' => new \App\Http\Resources\Stock\Sale($sale, true)
                ],
            ]
        ]);
    }

    /*
    * Get sale report (Norma rasxod)
    */
    public function reportMaterial(Request $request)
    {
        $report_materials_with_price = [];

        $sale_id = $request['sale_id'];        

        if ( $sale = Sale::find($sale_id) )
        {
            $output_materials = OutputMaterial::where('output_materialable_id',$sale->id)->where('output_materialable_type','sales')->get();
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
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data' => [
                    'report_materials' => $report_materials_with_price,
                ]
            ]
        ]);
    }
    

    public function printSaleMaterialWarehouse()
    {
        if (!$sale = $this->sale->with('saleable.client')->find(\request('sale_id'))){
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

        if( $sale->owner == \App\Sale::FOR_FIRM )
        {
            $client_name = "Фирма";
        }
        else
        {
            if( $sale->saleable )
            {
                if( $sale->saleable->client )
                {
                    $client_name = $sale->saleable->client->name;
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

        $sale_material_warehouses = [];

        $output_materials = OutputMaterial::where('output_materialable_id',$sale->id)->where('output_materialable_type','sales')->get();
        /*
         * Get materials info from output materials
         */
        if (count($output_materials) > 0){
            foreach ($output_materials as $output_material){
                array_push($sale_material_warehouses,[
                    'material'           => new \App\Http\Resources\Relation\Material($output_material->material),
                    'warehouse'          => $output_material->warehouse_material ? new \App\Http\Resources\Warehouse($output_material->warehouse_material->warehouse) : null,
                    'quantity'           => $output_material->quantity,
                    'price'              => $output_material->warehouse_material ? $output_material->warehouse_material->price : 0,
                    'total_amount'       => $output_material->warehouse_material ? $output_material->quantity * $output_material->warehouse_material->price : 0,
                ]);
            }
        }

        return view('sale_material_warehouse_invoice',compact('sale_material_warehouses','sale', 'client_name'));
    }

    public function printBarcode(Request $request)
    {
        $access_token = $request->header('AccessToken');
        $user = User::with('role')->where('access_token',$access_token)->first();

        if (!$sale = $this->sale->find(\request('sale_id'))){
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

        return view('sale_barcode',['sale' => $sale, 'user' => $user]);

        //         try {
        //     // Enter the share name for your USB printer here
        //     $connector = 'TSC TDP-245';
        //     //$connector = new WindowsPrintConnector("Receipt Printer");
        //     /* Print a "Hello world" receipt" */
        //     $printer = new Printer($connector);
        //     $printer -> text("Hello World!\n");
        //     $printer -> cut();

        //     /* Close printer */
        //     $printer -> close();
        // } catch (Exception $e) {
        //     echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
        // }

        // $ip = "192.168.123.1";
        // $connector = new NetworkPrintConnector($ip, 9100);
        // $connector = null;
        // $profile = CapabilityProfile::load("default");
        // Connect to printer
        // $connector = new WindowsPrintConnector("TSC TDP-245");
        // if ($printer) {
        //     $printer = new Printer($connector);
        //     $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        //     $printer -> text("HAYAT MEDICAL CENTER.\n");
        //     $printer -> selectPrintMode();
        //     $printer -> feed();
        //     $printer -> cut();
        //     $printer -> pulse();

        //     $printer -> close();
        // }
    }
}
