<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Auth extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'first_name'    => $this->first_name,
            'last_login'    => $this->last_login,
            'phone'         => $this->phone,
            'role'          => new Relation\Role($this->role,true),
            'token'         => $this->token,
        ];
    }
}
