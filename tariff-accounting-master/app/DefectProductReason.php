<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DefectProductReason extends Model
{
    protected $fillable = ['defect_product_id', 'reason_id', 'quantity'];

    public function defect_product(){
        return $this->belongsTo(DefectProduct::class)->withTrashed();
    }

    public function reason(){
        return $this->belongsTo(Reason::class)->withTrashed();
    }
}
