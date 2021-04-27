<?php

namespace App;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ScopeTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;

class ApplicationPart extends Model implements Auditable
{
    use ScopeTrait;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    const TABLE_NAME = 'application_parts';

    const ACTIVE = 'active';
    const INACTIVE = 'inactive';

    protected $fillable = [
        'application_id','start_date','stop_date','amount','paid','status'
    ];

    protected $casts = [
        'start_date'  => Controller::ELEMENT_DATE_FORMAT,
        'stop_date'   => Controller::ELEMENT_DATE_FORMAT,
        'amount' => 'float',
        'paid' => 'float',
        'created_at'  => Controller::ELEMENT_DATE_TIME_FORMAT,
        'updated_at'  => Controller::ELEMENT_DATE_TIME_FORMAT,
    ];

    protected $search_columns = ['id', 'start_date','stop_date','amount','paid'];

    public function scopeSearch($query, $string){
        $columns = $this->search_columns;
        $query->where(function ($query) use($string, $columns) {
            foreach ($columns as $column){
                $query->orwhere($column, 'like',  '%' . $string .'%');
            }
            $query->orWhereIn('application_id',Application::search($string)->pluck('id')->toArray());
        });
        return $query;
    }

    public function scopeFilter($query)
    {
        if ($filter = request('id')){
            $query = $query->where('id',$filter);
        }
        if ($filter = request('client_id')){
            $query = $query->whereHas('application', function (Builder $query) use ($filter){
                $query->where('client_id',$filter);
            });
        }
        if ($filter = request('console_number')){
            $query = $query->whereHas('application', function (Builder $query) use ($filter){
                $query->where('console_number','like','%' . $filter . '%');
            });
        }
        if ($filter = request('application_id')){
            $query = $query->where('application_id','like','%' . $filter . '%');
        }
        if ($filter = request('start_date')){
            $query = $query->whereDate('start_date','=',Carbon::parse($filter)->toDateString());
        }
        if ($filter = request('stop_date')){
            $query = $query->whereDate('stop_date','=',Carbon::parse($filter)->toDateString());
        }
        if ($filter = request('amount')){
            $query = $query->where('amount','like','%' . $filter . '%');
        }
        if ($filter = request('paid')){
            $query = $query->where('paid','like','%' . $filter . '%');
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

    public function scopeFilterInApplication($query)
    {
        if ($filter = request('client_id')){
            $query = $query->whereHas('application', function (Builder $query) use ($filter){
                $query->where('client_id',$filter);
            });
        }
        $from = request()->get('from_date',null);
        $to = request()->get('to_date',null);
        if ( $from && $to ){
            $query = $query->where(function ($query) use ($from, $to) {
                $query->whereDate('start_date','>=',Carbon::parse($from)->toDateString())
                    ->whereDate('start_date','<=',Carbon::parse($to)->toDateString());
            })->orWhere(function($query) use ($from, $to) {
                $query->whereDate('stop_date','>=',Carbon::parse($from)->toDateString())
                    ->whereDate('stop_date','<=',Carbon::parse($to)->toDateString());
            });
        }

        return $query;
    }

    public function scopeHavePaid($query)
    {
        return $query->where('paid', '>', 0);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::ACTIVE);
    }

    public function application() {
        return $this->belongsTo(Application::class);
    }
}
