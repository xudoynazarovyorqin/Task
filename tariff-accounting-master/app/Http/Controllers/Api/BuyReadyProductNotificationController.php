<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\BuyReadyProductNotificationCollection;
use App\Http\Resources\NotEnoughProductCollection;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\BuyReadyProductNotification;
use App\Http\Resources\BuyReadyProductNotificationResource;

class BuyReadyProductNotificationController extends Controller
{
    protected $response;

    protected $per_page;

    protected $buy_notification;

    protected $apiResponse;

    private $message_not_found;

    public function __construct(Response $response, ApiResponse $apiResponse, BuyReadyProductNotification $buy_notification)
    {
        $this->middleware('auth:api');

        $this->response = $response;
        $this->buy_notification = $buy_notification;
        $this->apiResponse = $apiResponse;
        $this->per_page = request()->get('per_page',1000000);
        $this->message_not_found = trans('messages.not_found',['name' => trans('messages.buy_ready_product_notification')]);
    }

    public function index()
    {
        $buy_notifications = $this->buy_notification;
        if ($str = \request('search'))
        {
            $buy_notifications = $buy_notifications->search($str);
        }

        $buy_notifications = $buy_notifications->filter();
        $buy_notifications = $buy_notifications->sort()->paginate($this->per_page);

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'    => new BuyReadyProductNotificationCollection($buy_notifications)
           ]
        ]);
    }

    public function show($id = null)
    {
        if ( !$notification = BuyReadyProductNotification::find($id) ){
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
                'notification'          => $notification,
                'not_enough_products'   => new NotEnoughProductCollection($notification->products)
            ],
        ]);
    }

    public function cancel(Request $request)
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

        $buy_notification_id = $request['buy_notification_id'];
        $reason = $request['reason'];

        if ( !$notification = BuyReadyProductNotification::find($buy_notification_id) ){
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

        $notification->update([
            'body' => $reason,
            'status'    => BuyReadyProductNotification::CANCELED
        ]);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => 'Успешно отменено',
            ]
        ]);
    }

    public function count(){
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'count' => BuyReadyProductNotification::where('status',BuyReadyProductNotification::CREATED)->count(),
            ]
        ]);
    }
}
