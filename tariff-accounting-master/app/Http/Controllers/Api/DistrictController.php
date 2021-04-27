<?php


namespace App\Http\Controllers\Api;


use App\District;
use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\DistrictRequest;
use App\Http\Resources\DistrictCollection;
use EllipseSynergie\ApiResponse\Laravel\Response;

class DistrictController extends Controller
{
    protected $response;

    protected $model;

    protected $per_page;

    protected $apiResponse;

    private $message_not_found;

    private $listIndex;

    private $modelIndex;

    public function __construct(Response $response, ApiResponse $apiResponse, District $model)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:districts.create')->only('store');
        $this->middleware('permission:districts.show')->only('show');
        $this->middleware('permission:districts.update')->only('update');
        $this->middleware('permission:districts.delete')->only(['destroy']);

        $this->response = $response;
        $this->apiResponse = $apiResponse;
        $this->model = $model;
        $this->per_page = request()->get('per_page' , 1000000);
        $this->message_not_found = trans('messages.not_found',['name' => __('messages.district')]);
        $this->listIndex = 'districts';
        $this->modelIndex = 'district';
    }

    public function index()
    {
        $districts = $this->model;
        if ($str = \request('search'))
        {
            $districts = $districts->search($str);
        }
        $districts = $districts->filter();
        $districts = $districts->sort()->paginate($this->per_page);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => new DistrictCollection($districts)
            ]
        ]);
    }

    public function inventory()
    {
        $districts = $this->model;
        if ($str = \request('search'))
        {
            $districts = $districts->search($str);
        }
        $districts = $districts->filter();
        $districts = $districts->sort()->paginate($this->per_page);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'  => new \App\Http\Resources\Inventory\DistrictCollection($districts)
            ],
        ]);
    }

    public function show(District $district)
    {
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    $this->modelIndex => new \App\Http\Resources\District($district,true)
                ]
            ]
        ]);
    }

    public function store(DistrictRequest $request)
    {
        $district = District::create([
            'name'              => $request['name'],
            'description'       => $request['description'],
        ]);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.store_success',['name' => __('messages.district')]),
                'data'    => [
                    $this->modelIndex => new \App\Http\Resources\District($district)
                ]
            ]
        ]);
    }

    public function update(DistrictRequest $request, District $district)
    {
        $district->update([
            'name'              => $request['name'],
            'description'       => $request['description'],
        ]);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.update_success',['name' => __('messages.district')]),
            ]
        ]);
    }

    public function destroy(District $district)
    {
        try {
            $district->delete();
        } catch (\Exception $e) {
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.destroy_success',['name' => __('messages.district')]),
                'data'    => [
                    'message' => trans('messages.successfully_deleted')
                ]
            ]
        ]);
    }
}
