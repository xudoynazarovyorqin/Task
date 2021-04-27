<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class BuyNotification extends JsonResource
{
    public function toArray($request)
    {
        return [
                'id'                        => $this->id,
                'buy_notificationable_id'   => $this->buy_notificationable_id,
                'buy_notificationable_type' => $this->buy_notificationable_type,
                'body'                      => $this->body,
                'status'                    => $this->status,
                'is_click_buttons'          => ($this->status == \App\BuyNotification::CREATED) ? 1 : 0,
                'created_at'                => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->created_at)),
                'updated_at'                => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->updated_at)),
        ];
    }
}
