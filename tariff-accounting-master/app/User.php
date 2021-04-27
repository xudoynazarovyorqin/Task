<?php

namespace App;

use App\Http\Controllers\Traits\ActivateTrait;
use App\Http\Controllers\Traits\ScopeTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    use ScopeTrait;

    use ActivateTrait;

    use SoftDeletes;

    const STATUS_ACTIVE = 'active';

    const STATUS_DEACTIVE = 'deactive';

    const VERIFIED = 1;

    const UNVERIFIED = 0;

    const LOCKED = 1;

    const UNLOCKED = 0;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','first_name','surname','role_id','phone','verified','locked','status','last_login','patronymic','access_token', 'is_employee', 'pin_code'
    ];

    private $search_columns = [
        'id','name', 'email','first_name','surname','role_id','phone','verified','locked','status','last_login','patronymic', 'is_employee', 'pin_code'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','pin_code', 'verified', 'locked', 'status', 'last_login', 'access_token'
    ];

    public function scopeFilter($query){
        if ($filter = request('id')){
            $query = $query->where('id','like','%'. $filter . '%');
        }
        if ($filter = request('name')){
            $query = $query->where('name','like','%' .  $filter . '%');
        }
        if ($filter = request('status')){
            $query = $query->where('status','like','%'. $filter . '%');
        }
        if ($filter = request('phone')){
            $query = $query->where('phone','like','%'. $filter . '%');
        }
        if ($filter = request('role_id')){
            $query = $query->where('role_id','like','%'. $filter . '%');
        }
        if ($filter = request('first_name')){
            $query = $query->where('first_name','like','%'. $filter . '%');
        }
        if ($filter = request('surname')){
            $query = $query->where('surname','like','%'. $filter . '%');
        }
        if ($filter = request('patronymic')){
            $query = $query->where('patronymic','like','%'. $filter . '%');
        }
        if ($filter = request('email')){
            $query = $query->where('email','like','%'. $filter . '%');
        }
        if ($filter = request('last_login')){
            $query = $query->where('last_login','like','%'. $filter . '%');
        }
        if ( request('is_employee') !== null ){
            $filter = request('is_employee');
            $query = $query->where('is_employee', $filter);
        }
        if ($filter = request('pin_code')){
            $query = $query->where('pin_code','like','%'. $filter . '%');
        }
        if ($filter = request('created_at')){
            $query = $query->whereDate('created_at','=',Carbon::parse($filter)->toDateString());
        }
        if ($filter = request('updated_at')){
            $query = $query->whereDate('updated_at','=',Carbon::parse($filter)->toDateString());
        }
        if ($from = request()->get('from_date',null)){
            $query = $query->whereDate('created_at','>=',Carbon::parse($from)->toDateString());
        }
        if ($to = request()->get('to_date',null)){
            $query = $query->whereDate('created_at','<=',Carbon::parse($to)->toDateString());
        }
        return $query;
    }


    public function role(){
        return $this->belongsTo(Role::class);
    }

    public function employee_groups(){
        return $this->belongsToMany(EmployeeGroup::class,'employee_group_users')->withTrashed();
    }

    public function sales(){
        return $this->belongsToMany(Sale::class,'sale_users');
    }
}
