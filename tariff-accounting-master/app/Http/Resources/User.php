<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    private $withParams;

    public function __construct($resource, $withParams = false)
    {
        $this->resource = $resource;
        $this->withParams = $withParams;
    }

    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'first_name'    => $this->first_name,
            'surname'       => $this->surname,
            'patronymic'    => $this->patronymic,
            'last_login'    => $this->last_login,
            'email'         => $this->email,
            'created_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->created_at)),
            'updated_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->updated_at)),
            'phone'         => $this->phone,
            'status'        => $this->status,
            'role'          => new Relation\Role($this->role),
            'is_employee'   => intval($this->is_employee),
            'pin_code'      => $this->pin_code,
            'employee_groups'   => $this->employee_groups,
            'sales'         => $this->sales,
        ];
    }
}
