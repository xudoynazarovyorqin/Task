<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\SaleProduct;
use App\BuyMaterial;


class StatisticController extends Controller
{
    /**
     * @var Response
     */
    protected $response;

    /**
     * @var ApiResponse
     */
    protected $apiResponse;
    private $message_not_found;

    public function __construct(Response $response, ApiResponse $apiResponse)
    {
        $this->middleware('auth:api');
        $this->response = $response;
        $this->apiResponse = $apiResponse;
        $this->per_page = request('per_page') ? request('per_page') : 1000000;
        $this->message_not_found = trans('messages.not_found',['name' => __('messages.cost')]);
    }

    public function productStatistic()
    {
        if (!$type = \request('type')){
            return response()->json([
                'result'  => [
                    'success' => false,
                ],
                'error' => [
                    'message' => __('messages.not_found',['name' => __('messages.diagram_type')])
                ]
            ]);
        }

        $data = [];

        if( \request('type') == self::WEEKLY )
        {
            $last_week = Carbon::now()->subWeek();

            $array = $this->getArrayProductByDay($last_week, \request('product_id'));

            for ($i=0; $i<7; $i++){
                $last_week = Carbon::parse($last_week)->addDay();
                $key = $last_week->format('Y-m-d');
                array_push($data,[
                    'label' => __('messages.' . Carbon::parse($key)->format('l')),
                    'sum'   => (isset($array[$key])) ? $array[$key]->sum('quantity') : 0,
                    'date'  => $key
                ]);
            }
        }
        else if( \request('type') == self::MONTHLY )
        {
            $last_month = Carbon::now()->subMonth();

            $array = $this->getArrayProductByDay($last_month, \request('product_id'));

            for ($i=0; $i<30; $i++){
                $last_month = Carbon::parse($last_month)->addDay();
                $key = $last_month->format('Y-m-d');
                array_push($data,[
                    'label' => $last_month->format('m-d'),
                    'sum'   => (isset($array[$key])) ? $array[$key]->sum('quantity') : 0,
                    'date'  => $key
                ]);
            }
        }
        else
        {
            $last_year = Carbon::now()->subYear();

            $array = $this->getArrayProductByMonth($last_year, \request('product_id'));

            for ($i=0; $i<12; $i++){
                $last_year = Carbon::parse($last_year)->addMonth();
                $key = $last_year->format('Y-m');
                array_push($data,[
                    'label' => __('messages.' . Carbon::parse($key)->format('F')),
                    'sum'   => (isset($array[$key])) ? $array[$key]->sum('quantity') : 0,
                    'date'  => $key
                ]);
            }            
        }

        return response()->json([
            'result' => [
                'success' => true,
                'data' => [
                    'chart_data' => $data,
                ]
            ],
        ]);
    }


    public function getArrayProductByDay($fromDate, $product_id)
    {
        if( $product_id )
        {
            $array = SaleProduct::selectRaw('quantity, created_at')
                ->whereDate('created_at','>',$fromDate)
                ->where('product_id', $product_id)
                ->orderBy('created_at','asc')
                ->get()
                ->groupBy(function ($data){
                    return Carbon::parse($data->created_at)->format('Y-m-d');
                });
        }
        else
        {
            $array = SaleProduct::selectRaw('quantity, created_at')
                ->whereDate('created_at','>',$fromDate)
                ->orderBy('created_at','asc')
                ->get()
                ->groupBy(function ($data){
                    return Carbon::parse($data->created_at)->format('Y-m-d');
                });
        }
        
        return $array;
    }

    public function getArrayProductByMonth($fromDate, $product_id)
    {
        if( $product_id )
        {
            $array = SaleProduct::selectRaw('quantity, created_at')
                ->whereDate('created_at','>',$fromDate)
                ->where('product_id', $product_id)
                ->orderBy('created_at','asc')
                ->get()
                ->groupBy(function ($data){
                    return Carbon::parse($data->created_at)->format('Y-m');
                });
        }
        else
        {
            $array = SaleProduct::selectRaw('quantity, created_at')
                ->whereDate('created_at','>',$fromDate)
                ->orderBy('created_at','asc')
                ->get()
                ->groupBy(function ($data){
                    return Carbon::parse($data->created_at)->format('Y-m');
                });
        }
        
        return $array;
    }


    public function materialStatistic()
    {
        if (!$type = \request('type')){
            return response()->json([
                'result'  => [
                    'success' => false,
                ],
                'error' => [
                    'message' => __('messages.not_found',['name' => __('messages.diagram_type')])
                ]
            ]);
        }

        $data = [];

        if( \request('type') == self::WEEKLY )
        {
            $last_week = Carbon::now()->subWeek();

            $array = $this->getArrayMaterialByDay($last_week, \request('material_id'));

            for ($i=0; $i<7; $i++){
                $last_week = Carbon::parse($last_week)->addDay();
                $key = $last_week->format('Y-m-d');
                array_push($data,[
                    'label' => __('messages.' . Carbon::parse($key)->format('l')),
                    'sum'   => (isset($array[$key])) ? $array[$key]->sum('qty_weight') : 0,
                    'date'  => $key
                ]);
            }
        }
        else if( \request('type') == self::MONTHLY )
        {
            $last_month = Carbon::now()->subMonth();

            $array = $this->getArrayMaterialByDay($last_month, \request('material_id'));

            for ($i=0; $i<30; $i++){
                $last_month = Carbon::parse($last_month)->addDay();
                $key = $last_month->format('Y-m-d');
                array_push($data,[
                    'label' => $last_month->format('m-d'),
                    'sum'   => (isset($array[$key])) ? $array[$key]->sum('qty_weight') : 0,
                    'date'  => $key
                ]);
            }
        }
        else
        {
            $last_year = Carbon::now()->subYear();

            $array = $this->getArrayMaterialByMonth($last_year, \request('material_id'));

            for ($i=0; $i<12; $i++){
                $last_year = Carbon::parse($last_year)->addMonth();
                $key = $last_year->format('Y-m');
                array_push($data,[
                    'label' => __('messages.' . Carbon::parse($key)->format('F')),
                    'sum'   => (isset($array[$key])) ? $array[$key]->sum('qty_weight') : 0,
                    'date'  => $key
                ]);
            }            
        }

        return response()->json([
            'result' => [
                'success' => true,
                'data' => [
                    'chart_data' => $data,
                ]
            ],
        ]);
    }


    public function getArrayMaterialByDay($fromDate, $material_id)
    {
        if( $material_id )
        {
            $array = BuyMaterial::selectRaw('qty_weight, created_at')
                ->whereDate('created_at','>',$fromDate)
                ->where('material_id', $material_id)
                ->orderBy('created_at','asc')
                ->get()
                ->groupBy(function ($data){
                    return Carbon::parse($data->created_at)->format('Y-m-d');
                });
        }
        else
        {
            $array = BuyMaterial::selectRaw('qty_weight, created_at')
                ->whereDate('created_at','>',$fromDate)
                ->orderBy('created_at','asc')
                ->get()
                ->groupBy(function ($data){
                    return Carbon::parse($data->created_at)->format('Y-m-d');
                });
        }
        
        return $array;
    }

    public function getArrayMaterialByMonth($fromDate, $material_id)
    {
        if( $material_id )
        {
            $array = BuyMaterial::selectRaw('qty_weight, created_at')
                ->whereDate('created_at','>',$fromDate)
                ->where('material_id', $material_id)
                ->orderBy('created_at','asc')
                ->get()
                ->groupBy(function ($data){
                    return Carbon::parse($data->created_at)->format('Y-m');
                });
        }
        else
        {
            $array = BuyMaterial::selectRaw('qty_weight, created_at')
                ->whereDate('created_at','>',$fromDate)
                ->orderBy('created_at','asc')
                ->get()
                ->groupBy(function ($data){
                    return Carbon::parse($data->created_at)->format('Y-m');
                });
        }
        
        return $array;
    }

}
