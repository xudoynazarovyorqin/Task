<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleCreatedInfo extends Model
{
    protected $fillable = ['sale_id','ip_address','user_agent','accept_language','jwt_token','user_id'];

    public function sale(){
        return $this->belongsTo(Sale::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
