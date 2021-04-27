<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;

class Reservation extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use SoftDeletes;

    use Auditable;

    const ABLE_TYPE = 'reservationable_type';
    const ABLE_ID = 'reservationable_id';

    const SOURCEABLE_TYPE = 'sourceable_type';
    const SOURCEABLE_ID = 'sourceable_id';
    const TABLE_NAME = 'reservations';

    protected $fillable = [self::ABLE_TYPE,self::ABLE_ID,self::SOURCEABLE_ID,self::SOURCEABLE_TYPE,'quantity','issued'];


    public function reservationable(){
        return $this->morphTo();
    }

    public function sourceable()
    {
        return $this->morphTo();
    }

    public function warehouse_material(){
        return $this->belongsTo(WarehouseMaterial::class);
    }

    public function scopeOldest($query, $column = null)
    {
        if (!$column) {
            $column = self::CREATED_AT;
        }

        return $query->orderBy($column, 'asc');
    }
}
