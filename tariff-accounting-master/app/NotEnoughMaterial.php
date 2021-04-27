<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotEnoughMaterial extends Model
{
    protected $fillable = ['buy_notification_id','material_id','quantity'];

    public function material(){
        return $this->belongsTo(Material::class)->withTrashed();
    }
}
