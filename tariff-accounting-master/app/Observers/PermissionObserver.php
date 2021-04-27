<?php

namespace App\Observers;

use App\Permission;
use Illuminate\Support\Facades\DB;

class PermissionObserver
{
    /**
     * Handle the permission "created" event.
     *
     * @param  \App\Permission  $permission
     * @return void
     */
    public function created(Permission $permission)
    {
        //
    }

    /**
     * Handle the permission "updated" event.
     *
     * @param  \App\Permission  $permission
     * @return void
     */
    public function updated(Permission $permission)
    {
        //
    }

    /**
     * Handle the permission "deleted" event.
     *
     * @param  \App\Permission  $permission
     * @return void
     */
    public function deleted(Permission $permission)
    {
        DB::table('role_permission')->where('permission_id',$permission->id)->delete();
    }

    /**
     * Handle the permission "restored" event.
     *
     * @param  \App\Permission  $permission
     * @return void
     */
    public function restored(Permission $permission)
    {
        //
    }

    /**
     * Handle the permission "force deleted" event.
     *
     * @param  \App\Permission  $permission
     * @return void
     */
    public function forceDeleted(Permission $permission)
    {
        //
    }
}
