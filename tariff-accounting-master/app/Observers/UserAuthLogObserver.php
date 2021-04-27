<?php

namespace App\Observers;

use App\UserAuthLog;

class UserAuthLogObserver
{
    public function creating(UserAuthLog $userAuthLog)
    {
        $userAuthLog->ip_address = request()->ip();
    }

    /**
     * Handle the user auth log "created" event.
     *
     * @param  \App\UserAuthLog  $userAuthLog
     * @return void
     */
    public function created(UserAuthLog $userAuthLog)
    {
        
    }

    /**
     * Handle the user auth log "updated" event.
     *
     * @param  \App\UserAuthLog  $userAuthLog
     * @return void
     */
    public function updated(UserAuthLog $userAuthLog)
    {
        //
    }

    /**
     * Handle the user auth log "deleted" event.
     *
     * @param  \App\UserAuthLog  $userAuthLog
     * @return void
     */
    public function deleted(UserAuthLog $userAuthLog)
    {
        //
    }

    /**
     * Handle the user auth log "restored" event.
     *
     * @param  \App\UserAuthLog  $userAuthLog
     * @return void
     */
    public function restored(UserAuthLog $userAuthLog)
    {
        //
    }

    /**
     * Handle the user auth log "force deleted" event.
     *
     * @param  \App\UserAuthLog  $userAuthLog
     * @return void
     */
    public function forceDeleted(UserAuthLog $userAuthLog)
    {
        //
    }
}
