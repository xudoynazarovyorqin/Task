<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentTypeCollection;
use App\PaymentType;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class PaymentTypeController extends Controller
{
    protected $response;

    protected $per_page;

    protected $paymentType;

    protected $apiResponse;

    private $message_not_found;

    public function __construct(Response $response, ApiResponse $apiResponse, PaymentType $paymentType)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:paymentTypes.create')->only('store');
        $this->middleware('permission:paymentTypes.show')->only('show');
        $this->middleware('permission:paymentTypes.update')->only('update');
        $this->middleware('permission:paymentTypes.delete')->only(['destroy','multipleDelete']);

        $this->response = $response;
        $this->paymentType = $paymentType;
        $this->apiResponse = $apiResponse;
        $this->per_page = request()->get('per_page',1000000);
        $this->message_not_found = trans('messages.not_found',['name' => __('messages.paymentType')]);
    }

    public function index()
    {
        $paymentTypes = $this->paymentType;
        if ($str = \request('search'))
        {
            $paymentTypes = $paymentTypes->search($str);
        }

        $paymentTypes = $paymentTypes->filter();
        $paymentTypes = $paymentTypes->sort()->paginate($this->per_page);

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'    => new PaymentTypeCollection($paymentTypes)
           ]
        ]);
    }

    public function inventory()
    {
        $paymentTypes = $this->paymentType;
        if ($str = \request('search'))
        {
            $paymentTypes = $paymentTypes->search($str);
        }

        $paymentTypes = $paymentTypes->filter();
        $paymentTypes = $paymentTypes->sort()->paginate($this->per_page);

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'  => new \App\Http\Resources\Inventory\PaymentTypeCollection($paymentTypes)
           ],
        ]);
    }

    public function show($id)
    {

        if (!$paymentType = $this->paymentType->find($id))
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
                    'paymentType' => new \App\Http\Resources\PaymentType($paymentType)
                ]
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:100',
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

        $paymentType = PaymentType::create($request->all());

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.store_success',['name' => __('messages.paymentType')]),
                'data'    => [
                    'paymentType' => new \App\Http\Resources\PaymentType($paymentType)
                ]
            ]
        ]);
    }


    public function update(Request $request, $id)
    {
        $paymentType = PaymentType::find($id);

        if (is_null($paymentType))
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

        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:100',
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

        $paymentType->update($request->all());

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.update_success',['name' => __('messages.paymentType')]),
                'data'    => [
                    'paymentType' => new \App\Http\Resources\PaymentType($paymentType)
                ]
            ]
        ]);
    }

    public function destroy($id)
    {
        $paymentType = PaymentType::find($id);

        if (is_null($paymentType))
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

        $paymentType->delete();

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.destroy_success',['name' => __('messages.paymentType')]),
                'data'    => [
                ]
            ]
        ]);
    }
}
