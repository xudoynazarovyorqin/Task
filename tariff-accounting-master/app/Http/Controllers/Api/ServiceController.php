<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Http\Resources\ServiceCollection;
use App\Service;
use EllipseSynergie\ApiResponse\Laravel\Response;

class ServiceController extends Controller
{
    protected $response;

    protected $model;

    protected $per_page;

    protected $apiResponse;

    private $message_not_found;

    private $listIndex;

    private $modelIndex;

    public function __construct(Response $response, ApiResponse $apiResponse, Service $model)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:services.create')->only('store');
        $this->middleware('permission:services.show')->only('show');
        $this->middleware('permission:services.update')->only('update');
        $this->middleware('permission:services.delete')->only(['destroy']);

        $this->response = $response;
        $this->apiResponse = $apiResponse;
        $this->model = $model;
        $this->per_page = request()->get('per_page' , 1000000);
        $this->message_not_found = trans('messages.not_found',['name' => __('messages.service')]);
        $this->listIndex = 'services';
        $this->modelIndex = 'service';
    }

    public function index()
    {
        $services = $this->model;
        if ($str = \request('search'))
        {
            $services = $services->search($str);
        }
        $services = $services->filter();
        $services = $services->sort()->paginate($this->per_page);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => new ServiceCollection($services)
            ]
        ]);
    }

    public function inventory()
    {
        $services = $this->model;
        if ($str = \request('search'))
        {
            $services = $services->search($str);
        }
        $services = $services->filter();
        $services = $services->sort()->paginate($this->per_page);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'  => new \App\Http\Resources\Inventory\ServiceCollection($services)
            ],
        ]);
    }

    public function show(Service $service)
    {
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    $this->modelIndex => new \App\Http\Resources\Service($service,true)
                ]
            ]
        ]);
    }

    public function store(ServiceRequest $request)
    {
        $service = Service::create([
            'name'              => $request['name'],
            'price'             => $request['price'],
            'measurement_id'    => $request['measurement_id']
        ]);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.store_success',['name' => __('messages.service')]),
                'data'    => [
                    $this->modelIndex => new \App\Http\Resources\Service($service)
                ]
            ]
        ]);
    }

    public function update(ServiceRequest $request, Service $service)
    {
        $service->update([
            'name'              => $request['name'],
            'price'             => $request['price'],
            'measurement_id'    => $request['measurement_id']
        ]);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.update_success',['name' => __('messages.service')]),
            ]
        ]);
    }

    public function destroy(Service $service)
    {
        try {
            $service->delete();
        } catch (\Exception $e) {
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.destroy_success',['name' => __('messages.service')]),
                'data'    => [
                    'message' => trans('messages.successfully_deleted')
                ]
            ]
        ]);
    }
}
