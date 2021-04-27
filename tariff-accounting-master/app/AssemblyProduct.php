<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssemblyProduct extends Model
{
    protected $fillable = ['assembly_id', 'product_id', 'total','ready','waiting_to_buy'];

    public function assembly(){
        return $this->belongsTo(Assembly::class)->withTrashed();
    }

    public function product(){
        return $this->belongsTo(Product::class)->withTrashed();
    }
}
