<?php

namespace App\Http\Controllers\Api;

use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    private $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function index()
    {
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'auto_reservation' => boolval(setting('auto_reservation')),
                    'auto_pay' => boolval(setting('auto_pay')),
                    'automatic_write_off' => boolval(setting('automatic_write_off')),
                    'control_materials_sort' => setting('control_materials_sort'),
                    'control_products_sort' => setting('control_products_sort'),
                    'number_money_product' => intval(setting('number_money_product')),
                    'number_money_material' => intval(setting('number_money_material')),
                    'number_quantity_product' => intval(setting('number_quantity_product')),
                    'number_quantity_material' => intval(setting('number_quantity_material')),
                    'default_warehouse_user_id' => intval(setting('default_warehouse_user_id'))
                ],
            ]
        ]);
    }

    public function store(Request $request)
    {
        setting(['auto_reservation' => boolval($request['auto_reservation'])])->save();
        setting(['auto_pay' => boolval($request['auto_pay'])])->save();
        setting(['automatic_write_off' => boolval($request['automatic_write_off'])])->save();
        setting(['control_materials_sort' => $request['control_materials_sort']])->save();
        setting(['control_products_sort' => $request['control_products_sort']])->save();
        setting(['number_money_product' => intval($request['number_money_product'])])->save();
        setting(['number_money_material' => intval($request['number_money_material'])])->save();
        setting(['number_quantity_product' => intval($request['number_quantity_product'])])->save();
        setting(['number_quantity_material' => intval($request['number_quantity_material'])])->save();
        setting(['default_warehouse_user_id' => intval($request['default_warehouse_user_id'])])->save();

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.update_success',['name' => trans('messages.settings')]),
                'data'    => [
                    'auto_reservation' => boolval(setting('auto_reservation')),
                    'auto_pay' => boolval(setting('auto_pay')),
                    'automatic_write_off' => boolval(setting('automatic_write_off')),
                    'control_materials_sort' => setting('control_materials_sort'),
                    'control_products_sort' => setting('control_products_sort'),
                    'number_money_product' => intval(setting('number_money_product')),
                    'number_money_material' => intval(setting('number_money_material')),
                    'number_quantity_product' => intval(setting('number_quantity_product')),
                    'number_quantity_material' => intval(setting('number_quantity_material')),
                    'default_warehouse_user_id' => intval(setting('default_warehouse_user_id')),
                ],
             ]
        ]);
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
