<?php

namespace App;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ScopeTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Application extends Model implements Auditable
{
    use ScopeTrait;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    const TABLE_NAME = 'applications';

    protected $fillable = [
        'number','datetime',
        'client_id', 'contract_client_id', 'status_id', 'console_number','amount',
        'object_name', 'district_id', 'quarter_id', 'object_street', 'object_home', 'object_corps', 'object_flat',
    ];

    protected $casts = [
        'amount' => 'float',
        'total_amount' => 'float',
        'total_paid' => 'float',
        'total_not_paid' => 'float',
        'total_transaction_paid' => 'float',
        'datetime'    => Controller::ELEMENT_DATE_TIME_FORMAT,
        'created_at'  => Controller::ELEMENT_DATE_TIME_FORMAT,
        'updated_at'  => Controller::ELEMENT_DATE_TIME_FORMAT,
    ];

    protected $search_columns = ['id', 'number','datetime','console_number','amount','object_name','object_street','object_home','object_corps', 'object_flat'];

    public function scopeSearch($query, $string){
        $columns = $this->search_columns;
        $query->where(function ($query) use($string, $columns) {
            foreach ($columns as $column){
                $query->orwhere($column, 'like',  '%' . $string .'%');
            }
            $query->orWhereIn('client_id',Client::search($string)->pluck('id')->toArray());
            $query->orWhereIn('status_id',State::search($string)->pluck('id')->toArray());
        });
        return $query;
    }

    public function scopeFilter($query)
    {
        if ($filter = request('id')){
            $query = $query->where('applications.id',$filter);
        }
        if ($filter = request('number')){
            $query = $query->where('number',$filter);
        }
        if ($filter = request('datetime')){
            $query = $query->whereDate('datetime','=',Carbon::parse($filter)->toDateString());
        }
        if ($filter = request('client_id')){
            $query = $query->where('client_id',$filter);
        }
        if ($filter = request('client_phone')){
            $query = $query->whereIn('client_id',Client::where('phone', 'like', '%' . $filter . '%')->pluck('id')->toArray());
        }
        if ($filter = request('contract_client_id')){
            $query = $query->where('contract_client_id',$filter);
        }
        if ($filter = request('status_id')){
            $query = $query->where('status_id',$filter);
        }
        if ($filter = request('console_number')){
            $query = $query->where('console_number','like','%' . $filter . '%');
        }
        if ($filter = request('amount')){
            $query = $query->where('applications.amount','like','%' . $filter . '%');
        }
        if ($filter = request('object_name')){
            $query = $query->where('object_name','like','%' . $filter . '%');
        }
        if ($filter = request('district_id')){
            $query = $query->where('district_id',$filter);
        }
        if ($filter = request('quarter_id')){
            $query = $query->where('quarter_id',$filter);
        }
        if ($filter = request('object_address')){
            $query = $query->where(function ($query) use($filter) {
                $query->orWhere('applications.object_street','like','%' . $filter . '%');
                $query->orWhere('applications.object_home','like','%' . $filter . '%');
                $query->orWhere('applications.object_flat','like','%' . $filter . '%');
            });
        }
        if ($filter = request('object_street')){
            $query = $query->where('object_street','like','%' . $filter . '%');
        }
        if ($filter = request('object_home')){
            $query = $query->where('object_home','like','%' . $filter . '%');
        }
        if ($filter = request('object_corps')){
            $query = $query->where('object_corps','like','%' . $filter . '%');
        }
        if ($filter = request('object_flat')){
            $query = $query->where('object_flat','like','%' . $filter . '%');
        }
        if ($filter = request('created_at')){
            $query = $query->whereDate('applications.created_at','=',Carbon::parse($filter)->toDateString());
        }
        if ($filter = request('updated_at')){
            $query = $query->whereDate('applications.updated_at','=',Carbon::parse($filter)->toDateString());
        }
        if ($from = request()->get('from_date',null)){
            $query = $query->whereDate('datetime','>=',Carbon::parse($from)->toDateString());
        }
        if ($to = request()->get('to_date',null)){
            $query = $query->whereDate('datetime','<=',Carbon::parse($to)->toDateString());
        }

        return $query;
    }

    public function scopeFilterByPayment($query, $status_by_payment){
        // tablitsa tepasidigi knopkala
        if ( $status_by_payment == 'new_applications' ) {
            $query->where(function($query) {
                return $query->whereDoesntHave('parts', function (Builder $query) {
                    $query->where('status', ApplicationPart::ACTIVE);
                });
            });
        }
        else if( $status_by_payment == 'payment_pending' ) {
            $query->where(function($query) {
                return $query->orwhere(function ($query) {
                    return $query->whereDoesntHave('parts', function (Builder $query) {
                        $query->where('start_date','like','%' . date('Y-m', strtotime('+1 month')) . '%')
                            ->where('status', ApplicationPart::ACTIVE);
                    });
                })->orwhere(function ($query) {
                    return $query->whereHas('parts', function (Builder $query) {
                        $query->where('start_date','like','%' . date('Y-m', strtotime('+1 month')) . '%')
                            ->where('status', ApplicationPart::ACTIVE)
                            ->whereRaw('amount - paid > 0');
                    });
                });
            });
        }
        else if( $status_by_payment == 'overdue' ) {
            $query->where(function($query) {
                return $query->orwhere(function ($query) {
                    return $query->whereDoesntHave('parts', function (Builder $query) {
                        $query->where('start_date','like', '%' . date('Y-m') . '%')
                            ->where('status', ApplicationPart::ACTIVE);
                    });
                })->orwhere(function ($query) {
                    return $query->whereHas('parts', function (Builder $query) {
                        $query->where('start_date','like', '%' . date('Y-m') . '%')
                            ->where('status', ApplicationPart::ACTIVE)
                            ->whereRaw('amount - paid > 0');
                    });
                });
            });
        }

        return $query;
    }

    public function audits(): MorphMany
    {
        return $this->morphMany(Audit::class,'auditable');
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(\Goodoneuz\PayUz\Models\Transaction::class,'transactionable');
    }

    public function services(){
        return $this->hasMany(ApplicationService::class);
    }

    public function parts(){
        return $this->hasMany(ApplicationPart::class);
    }

    public function client(){
        return $this->belongsTo(Client::class);
    }

    public function contract_client(){
        return $this->belongsTo(ContractClient::class);
    }

    public function status(){
        return $this->belongsTo(State::class);
    }

    public function district(){
        return $this->belongsTo(District::class);
    }

    public function quarter(){
        return $this->belongsTo(Quarter::class);
    }
}
