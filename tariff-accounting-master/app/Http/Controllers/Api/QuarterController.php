<?php


namespace App\Http\Controllers\Api;


use App\Http\Requests\QuarterRequest;
use App\Http\Resources\QuarterCollection;
use App\Quarter;
use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use EllipseSynergie\ApiResponse\Laravel\Response;

class QuarterController extends Controller
{
    protected $response;

    protected $model;

    protected $per_page;

    protected $apiResponse;

    private $message_not_found;

    private $listIndex;

    private $modelIndex;

    public function __construct(Response $response, ApiResponse $apiResponse, Quarter $model)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:quarters.create')->only('store');
        $this->middleware('permission:quarters.show')->only('show');
        $this->middleware('permission:quarters.update')->only('update');
        $this->middleware('permission:quarters.delete')->only(['destroy']);

        $this->response = $response;
        $this->apiResponse = $apiResponse;
        $this->model = $model;
        $this->per_page = request()->get('per_page' , 1000000);
        $this->message_not_found = trans('messages.not_found',['name' => __('messages.quarter')]);
        $this->listIndex = 'quarters';
        $this->modelIndex = 'quarter';
    }

    public function index()
    {
        $quarters = $this->model;
        if ($str = \request('search'))
        {
            $quarters = $quarters->search($str);
        }
        $quarters = $quarters->filter();
        $quarters = $quarters->sort()->paginate($this->per_page);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => new QuarterCollection($quarters)
            ]
        ]);
    }

    public function inventory()
    {
        $quarters = $this->model;
        if ($str = \request('search'))
        {
            $quarters = $quarters->search($str);
        }
        $quarters = $quarters->filter();
        $quarters = $quarters->sort()->paginate($this->per_page);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'  => new \App\Http\Resources\Inventory\QuarterCollection($quarters)
            ],
        ]);
    }

    public function show(Quarter $quarter)
    {
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    $this->modelIndex => new \App\Http\Resources\Quarter($quarter,true)
                ]
            ]
        ]);
    }

    public function store(QuarterRequest $request)
    {
        $quarter = Quarter::create([
            'name'              => $request['name'],
            'description'       => $request['description'],
        ]);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.store_success',['name' => __('messages.quarter')]),
                'data'    => [
                    $this->modelIndex => new \App\Http\Resources\Quarter($quarter)
                ]
            ]
        ]);
    }

    public function update(QuarterRequest $request, Quarter $quarter)
    {
        $quarter->update([
            'name'              => $request['name'],
            'description'       => $request['description'],
        ]);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.update_success',['name' => __('messages.quarter')]),
            ]
        ]);
    }

    public function destroy(Quarter $quarter)
    {
        try {
            $quarter->delete();
        } catch (\Exception $e) {
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.destroy_success',['name' => __('messages.quarter')]),
                'data'    => [
                    'message' => trans('messages.successfully_deleted')
                ]
            ]
        ]);
    }
}
