<?php
/**
 * Dilmurod
 * Azizbek
 */
namespace App;

use App\Http\Controllers\Traits\ScopeTrait;
use Carbon\Carbon;
use OwenIt\Auditing\Models\Audit as AuditParent;

class Audit extends AuditParent
{
    use ScopeTrait;

    private $search_columns = [
        'id', 'user_id', 'event', 'auditable_type', 'auditable_id', 'ip_address', 'created_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function auditable()
    {
        return $this->morphTo();
    }

    public function scopeFilter($query){
        if ($filter = request('id')){
            $query = $query->where('id','like','%'. $filter . '%');
        }
        if ($filter = request('user_id')){
            $query = $query->where('user_id', $filter);
        }
        if ($filter = request('event')){
            $query = $query->where('event','like','%'. $filter . '%');
        }
        if ($filter = request('auditable_type')){
            $query = $query->where('auditable_type', 'like','%'. $filter . '%');
        }
        if ($filter = request('auditable_id')){
            $query = $query->where('auditable_id', $filter);
        }
        if ($filter = request('ip_address')){
            $query = $query->where('ip_address','like','%'. $filter . '%');
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
}
