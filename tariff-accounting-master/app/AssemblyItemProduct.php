<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssemblyItemProduct extends Model
{
    protected $fillable = ['assembly_item_id','product_id','quantity','ready'];

    public function assembly_item(){
        return $this->belongsTo(AssemblyItem::class)->withTrashed();
    }

    public function product(){
        return $this->belongsTo(Product::class)->withTrashed();
    }
}
