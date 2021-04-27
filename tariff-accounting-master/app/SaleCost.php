<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleCost extends Model
{
    protected $fillable = ['sale_id','cost_id','amount'];

    public function sale(){
        return $this->belongsTo(Sale::class);
    }

    public function cost(){
        return $this->belongsTo(Cost::class);
    }
}
