<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Resources\Inventory\CategoryCollection;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    protected $response;

    protected $per_page;

    protected $category;

    protected $apiResponse;

    private $message_not_found;

    public function __construct(Response $response, ApiResponse $apiResponse, Category $category)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:categories.create')->only('store');
        $this->middleware('permission:categories.show')->only('show');
        $this->middleware('permission:categories.update')->only('update');
        $this->middleware('permission:categories.delete')->only(['destroy']);

        $this->response = $response;
        $this->category = $category;
        $this->apiResponse = $apiResponse;
        $this->per_page = request()->get('per_page' , 10000000);
        $this->message_not_found = trans('strings.not_found',['name' => __('messages.category')]);
    }

    public function index()
    {
        $categories = $this->category;

        if ($str = \request('search'))
        {
            $categories = $categories->search($str);
        }

        $categories = $categories->filter();
        $categories = $categories->sort()->paginate($this->per_page);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => new \App\Http\Resources\CategoryCollection($categories)
            ]
        ]);
    }

    public function inventory()
    {
        $categories = $this->category;

        if ($str = \request('search'))
        {
            $categories = $categories->search($str);
        }

        $categories = $categories->filter();
        $categories = $categories->sort()->paginate($this->per_page);

        return $this->response->withArray([
            'result' => [
                'success'   => true,
                'data'      => new CategoryCollection($categories)
            ],
        ]);
    }


    public function show(Category $category)
    {
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'category' => new \App\Http\Resources\Category($category)
                ]
            ]
        ]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required',
        ]);

        if ( $validator->fails() )
        {
            return $this->response->withArray([
                'result' => [ 'success'   => false, 'data' => []],
                'error' => [
                    'message'   => __('messages.validation_error'),
                    'code'      => ApiResponse::VALIDATION_ERROR,
                ],
                'validation_errors' => $validator->errors()
            ])->setStatusCode(ApiResponse::VALIDATION_ERROR);
        }

        $category = Category::create($request->all());

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.store_success',['name' => __('messages.category')]),
                'data'    => [
                    'category' => new \App\Http\Resources\Category($category)
                ]
            ]
        ]);
    }

    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:100',
        ]);

        if ( $validator->fails() )
        {
            return $this->response->withArray([
                'result' => [ 'success'   => false, 'data' => []],
                'error' => [
                    'message'   => __('messages.validation_error'),
                    'code'      => ApiResponse::VALIDATION_ERROR,
                    'validation_errors' => $validator->errors()
                ]
            ])->setStatusCode(ApiResponse::VALIDATION_ERROR);
        }

        $category->update($request->all());

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.update_success',['name' => __('messages.category')]),
                'data'    => [
                    'category' => new \App\Http\Resources\Category($category)
                ]
            ]
        ]);
    }


    public function destroy(Category $category)
    {
        try {
            $category->delete();
        } catch (\Exception $e) {
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.destroy_success',['name' => __('messages.category')]),
                'data'    => [
                    'message' => trans('messages.successfully_deleted')
                ]
            ]
        ]);
    }
}
