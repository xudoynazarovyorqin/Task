<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use App\Country;
use App\Http\Resources\Inventory\CountryCollection;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    protected $response;

    protected $per_page;

    protected $country;

    protected $apiResponse;

    private $message_not_found;

    public function __construct(Response $response, ApiResponse $apiResponse, Country $country)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:countries.create')->only('store');
        $this->middleware('permission:countries.show')->only('show');
        $this->middleware('permission:countries.update')->only('update');
        $this->middleware('permission:countries.delete')->only(['destroy']);

        $this->response = $response;
        $this->country = $country;
        $this->apiResponse = $apiResponse;
        $this->per_page = request()->get('per_page' ,1000000);
        $this->message_not_found = trans('messages.not_found',['name' => __('messages.country')]);
    }

    public function index()
    {
        $countries = $this->country;
        if ($str = \request('search'))
        {
            $countries = $countries->search($str);
        }

        $countries = $countries->filter();
        $countries = $countries->sort()->paginate($this->per_page);

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'    => new \App\Http\Resources\CountryCollection($countries)
           ]
        ]);
    }
    public function inventory()
    {
        $countries = $this->country;
        if ($str = \request('search'))
        {
            $countries = $countries->search($str);
        }

        $countries = $countries->filter();
        $countries = $countries->sort()->paginate($this->per_page);

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'  => new CountryCollection($countries)
           ]
        ]);
    }

    public function show(Country $country)
    {
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'country' => new \App\Http\Resources\Country($country)
                ]
            ]
        ]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:100',
            'full_name'     => 'string|max:255',
            'code'          => 'numeric',
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

        $country = Country::create($request->all());

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.store_success',['name' => __('messages.country')]),
                'data'    => [
                    'country' => new \App\Http\Resources\Country($country)
                ]
            ]
        ]);
    }

    public function update(Request $request, Country $country)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:100',
            'full_name'     => 'string|max:255',
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

        $country->update($request->all());

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.update_success',['name' => __('messages.country')]),
                'data'    => [
                    'country' => new \App\Http\Resources\Country($country)
                ]
            ]
        ]);
    }

    public function destroy(Country $country)
    {
        try {
            $country->delete();
        } catch (\Exception $e) {
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.destroy_success',['name' => __('messages.country')]),
                'data'    => [
                ]
            ]
        ]);
    }
}
