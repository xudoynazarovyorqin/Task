<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssemblyUser extends Model
{
    protected $fillable = ['assembly_id', 'user_id', 'employee_group_id'];

    public function sale(){
        return $this->belongsTo(Assembly::class)->withTrashed();
    }

    public function user(){
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function employee_group(){
        return $this->belongsTo(EmployeeGroup::class)->withTrashed();
    }
}
