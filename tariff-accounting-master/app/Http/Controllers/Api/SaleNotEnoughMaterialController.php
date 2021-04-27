<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Sale;
use App\SaleNotEnoughMaterial;
use App\SaleNotEnoughMaterialNotification;
use App\SaleProduct;
use App\Product;
use App\Material;
use App\Http\Controllers\Api\BuyController;

class SaleNotEnoughMaterialController extends Controller
{
    /**
     * @var Response
     */
    protected $response;

    public function __construct(Response $response)
    {
        $this->middleware('auth:api');
        $this->response = $response;
    }

    public function getNotEnoughMaterials()
    {
        $list = SaleNotEnoughMaterial::all();

        $notEnoughMaterials = [];

        foreach ($list as $item)
        {
            if( !isset($notEnoughMaterials[$item->sale_id]) )
            {
                $notification = SaleNotEnoughMaterialNotification::where('sale_id', $item->sale_id)->first();
                $notEnoughMaterials[$item->sale_id] = array();
                $notEnoughMaterials[$item->sale_id]['qty_for_sale'] = 0;
                $notEnoughMaterials[$item->sale_id]['status'] = $notification->status;
                $notEnoughMaterials[$item->sale_id]['body'] = $notification->body;
                if( !($notification->status == SaleNotEnoughMaterialNotification::CREATED) )
                {
                    $notEnoughMaterials[$item->sale_id]['is_click_buttons'] = 0;
                }
                else
                {
                    $notEnoughMaterials[$item->sale_id]['is_click_buttons'] = 1;
                }
                $notEnoughMaterials[$item->sale_id]['sale_id'] = $item->sale_id;
                $notEnoughMaterials[$item->sale_id]['created_at'] = date(Controller::EXCEL_DATE_FORMAT,strtotime($item->created_at));
                $notEnoughMaterials[$item->sale_id]['updated_at'] = date(Controller::EXCEL_DATE_FORMAT,strtotime($item->updated_at));
            }

            if( !isset($notEnoughMaterials[$item->sale_id][$item->material_id]) )
            {
                if( $one_material = Material::find($item->material_id) )
                {
                    $notEnoughMaterials[$item->sale_id][$item->material_id] = array();
                    $notEnoughMaterials[$item->sale_id][$item->material_id]['material_name'] = $one_material->name;
                    $notEnoughMaterials[$item->sale_id][$item->material_id]['measurement'] = ($one_material->measurement) ? $one_material->measurement->name : '';
                    $notEnoughMaterials[$item->sale_id][$item->material_id]['qty_for_material'] = 0;
                    $notEnoughMaterials[$item->sale_id][$item->material_id]['material_id'] = $item->material_id;
                }
            }

            $notEnoughMaterials[$item->sale_id][$item->material_id]['qty_for_material'] = $item->quantity;
            $notEnoughMaterials[$item->sale_id]['qty_for_sale'] += $notEnoughMaterials[$item->sale_id][$item->material_id]['qty_for_material'];
        }

        $notEnoughMaterials = array_reverse($notEnoughMaterials);

        return $notEnoughMaterials;
    }

    public function notEnoughMaterials()
    {
        $notEnoughMaterials = $this->getNotEnoughMaterials();

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'notEnoughMaterials'  => $notEnoughMaterials
                ]
            ]
        ]);
    }

    public function canceledStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reason'       => 'required',
        ]);

        if ($validator->fails()) {
            return $this->response->withArray(
                [
                    'result' => [
                        'success' => false,
                        'data'     => []
                    ],
                    'error' => [
                        'message' => __('messages.error'),
                        'code'    => ApiResponse::VALIDATION_ERROR
                    ],
                    'validation_errors' => $validator->errors()
                ])->setStatusCode(ApiResponse::VALIDATION_ERROR);
        }

        $sale_id = $request['sale_id'];
        $reason = $request['reason'];

        if ( !$notification = SaleNotEnoughMaterialNotification::where('sale_id', $sale_id)->first() ){
            return $this->response->withArray([
                'result' => [
                    'success' => false,
                    'data'    => null
                ],
                'error' => [
                    'message'   => trans('messages.not_found',['name' => trans('messages.notification')]),
                    'code'      => ApiResponse::PAGE_NOT_FOUND
                ]
            ])->setStatusCode(ApiResponse::PAGE_NOT_FOUND);
        }

        $notification->update([
            'body' => $reason,
            'status'    => SaleNotEnoughMaterialNotification::CANCELED
        ]);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => 'Успешно отменено',
                'data'    => [
                    'notification'  => $notification,
                ]
            ]
        ]);
    }    

    public function showCreateBuy(Request $request)
    {
        if ( !$saleNotEnoughMaterialNotification = SaleNotEnoughMaterialNotification::where('sale_id', $request['sale_id'])->first() ){
            return $this->response->withArray([
                'result' => [
                    'success' => false,
                    'data'    => null
                ],
                'error' => [
                    'message'   => trans('messages.not_found',['name' => trans('messages.sale_not_enough_material_notification')]),
                    'code'      => ApiResponse::PAGE_NOT_FOUND
                ]
            ])->setStatusCode(ApiResponse::PAGE_NOT_FOUND);
        }

        $saleNotEnoughMaterialNotification->load('materials.material.measurement', 'materials.material.warehouse_type');

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'saleNotEnoughMaterialNotification'  => $saleNotEnoughMaterialNotification,
                ]
            ]
        ]);
    }


}
