<?php

namespace App\Http\Controllers\Api;

use App\Events\Copy\CopyMaterialEvent;
use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\MaterialRequest;
use App\Http\Resources\MaterialCollection;
use App\Material;
use EllipseSynergie\ApiResponse\Laravel\Response;

class MaterialController extends Controller
{
    protected $response;

    protected $per_page;

    protected $material;

    protected $apiResponse;

    private $message_not_found;

    public function __construct(Response $response, ApiResponse $apiResponse, Material $material)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:materials.create')->only('store');
        $this->middleware('permission:materials.show')->only('show');
        $this->middleware('permission:materials.update')->only('update');
        $this->middleware('permission:materials.delete')->only(['destroy']);
        $this->middleware('permission:materials.copy')->only(['copy']);

        $this->response = $response;
        $this->material = $material;
        $this->apiResponse = $apiResponse;
        $this->per_page = request('per_page') ? request('per_page') : 1000000;
        $this->message_not_found = trans('messages.not_found',['name' => trans('messages.material')]);
    }

    public function index()
    {
        $materials = $this->material;
        if ($str = \request('search'))
        {
            $materials = $materials->search($str);
        }
        $materials = $materials->filter();
        $materials = $materials->sort()->paginate($this->per_page);

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'    => new MaterialCollection($materials)
           ]
        ]);
    }

    public function inventory()
    {
        $materials = $this->material;
        if ($str = \request('search'))
        {
            $materials = $materials->search($str);
        }

        $materials = $materials->filter();
        $materials = $materials->sort()->paginate($this->per_page);

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'  => new \App\Http\Resources\Inventory\MaterialCollection($materials)
           ],
        ]);
    }

    public function show(Material $material)
    {
        $data = [
            'material'  => new \App\Http\Resources\Material($material)
        ];

        if (\request()->get('warehouse_info',false)){
            $data['available']  = $material->warehouse_materials()->sum('remainder');
            $data['booked'] = $material->warehouse_materials()->sum('booked');
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'  => $data
            ]
        ]);
    }

    public function store(MaterialRequest $request)
    {
        $material = Material::create($request->all());

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.store_success',['name' => __('messages.material')]),
                'data'    => [
                    'material'  => new \App\Http\Resources\Material($material)
                ]
            ]
        ]);
    }

    public function update(MaterialRequest $request, Material $material)
    {
        $material->update($request->all());

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.update_success',['name' => __('messages.material')]),
            ]
        ]);
    }

    public function destroy(Material $material)
    {
        try {
            $material->delete();
        } catch (\Exception $e) {
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.destroy_success',['name' => __('messages.material')]),
                'data'    => [
                    'message' => trans('messages.successfully_deleted')
                ]
            ]
        ]);
    }

    public function getTypes()
    {
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'types'  => Material::getTypes()
                ]
            ]
        ]);
    }

    public function getReworkingMaterials()
    {
        $materials = $this->material->reworking()->get();

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'materials'  => $materials
                ]
            ]
        ]);
    }

    public function copy(){

        if (!$model = $this->material::find(\request()->get('id',null))){
            return $this->response->withArray([
                'result'    => [
                    'success' => false,
                    'data'    => ''
                ],
                'error' => [
                    'code' => ApiResponse::PAGE_NOT_FOUND,
                    'message' => $this->message_not_found
                ]
            ]);
        }

        $event = new CopyMaterialEvent($model);
        event($event);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.copy_success',['name' => __('messages.material')]),
            ],
        ]);
    }
}
