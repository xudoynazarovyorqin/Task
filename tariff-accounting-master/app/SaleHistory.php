<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleHistory extends Model
{
    use SoftDeletes;

    protected $fillable = ['sale_id','comment','level_id','user_id'];

    public function sale(){
        return $this->belongsTo(Sale::class);
    }

    public function level(){
        return $this->belongsTo(Level::class)->withTrashed();
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
