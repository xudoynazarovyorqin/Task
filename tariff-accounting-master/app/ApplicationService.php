<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ApplicationService extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['application_id','service_id', 'price'];

    public function application(){
        return $this->belongsTo(Application::class)->withTrashed();
    }

    public function service(){
        return $this->belongsTo(Service::class)->withTrashed();
    }
}
