<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeGroupUser extends Model
{
    protected $fillable = ['user_id','employee_group_id'];

    public function user(){
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function employee_group(){
        return $this->belongsTo(EmployeeGroup::class)->withTrashed();
    }
}
