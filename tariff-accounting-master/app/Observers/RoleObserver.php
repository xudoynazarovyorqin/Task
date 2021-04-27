<?php

namespace App\Observers;

use App\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoleObserver
{
    public function creating(Role $role)
    {
    }
    /**
     * Handle the role "created" event.
     *
     * @param  \App\Role  $role
     * @return void
     */
    public function created(Role $role)
    {
    }

    /**
     * Handle the role "updated" event.
     *
     * @param  \App\Role  $role
     * @return void
     */
    public function updated(Role $role)
    {
        //
    }

    /**
     * Handle the role "deleted" event.
     *
     * @param  \App\Role  $role
     * @return void
     */
    public function deleted(Role $role)
    {
        DB::table('role_permission')->where('role_id',$role->id)->delete();
    }

    /**
     * Handle the role "restored" event.
     *
     * @param  \App\Role  $role
     * @return void
     */
    public function restored(Role $role)
    {
        //
    }

    /**
     * Handle the role "force deleted" event.
     *
     * @param  \App\Role  $role
     * @return void
     */
    public function forceDeleted(Role $role)
    {
        //
    }
}
