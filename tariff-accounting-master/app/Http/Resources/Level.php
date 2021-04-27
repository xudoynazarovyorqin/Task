<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class Level extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'color'     => $this->color,
            'left'      => new \App\Http\Resources\Relation\Level($this->left_level) ,
            'right'     => new \App\Http\Resources\Relation\Level($this->right_level),
            'deletable' => $this->deletable,
            'sales_count' => count(\App\Sale::where('level_id',$this->id)->get()),
            'created_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->created_at)),
            'updated_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->updated_at)),
        ];
    }
}
