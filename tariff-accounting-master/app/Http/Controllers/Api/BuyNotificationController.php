<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ApiModelNotFoundException;
use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\BuyNotificationCollection;
use App\Http\Resources\NotEnoughMaterialCollection;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\BuyNotification;

class BuyNotificationController extends Controller
{
    protected $response;

    protected $per_page;

    protected $buy_notification;

    protected $apiResponse;

    private $message_not_found;

    public function __construct(Response $response, ApiResponse $apiResponse, BuyNotification $buy_notification)
    {
        $this->middleware('auth:api');
        $this->response = $response;
        $this->buy_notification = $buy_notification;
        $this->apiResponse = $apiResponse;
        $this->per_page = request()->get('per_page',1000000);
        $this->message_not_found = trans('messages.not_found',['name' => trans('messages.buy_notification')]);
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
               'data'    => new BuyNotificationCollection($buy_notifications),
           ]
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

        if ( !$notification = BuyNotification::find($buy_notification_id) ){
            throw new ApiModelNotFoundException(trans('messages.not_found',['name' => trans('messages.notification')]));
        }

        $notification->update([
            'body' => $reason,
            'status'    => BuyNotification::CANCELED
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

    public function show($id = null)
    {
        if ( !$notification = BuyNotification::find($id) ){
            throw new ApiModelNotFoundException(trans($this->message_not_found));
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'notification' => new \App\Http\Resources\BuyNotification($notification),
                'not_enough_materials' => new NotEnoughMaterialCollection($notification->materials)
            ]
        ]);
    }

    public function count(){
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'count' => BuyNotification::where('status',BuyNotification::CREATED)->count(),
            ]
        ]);
    }

}
