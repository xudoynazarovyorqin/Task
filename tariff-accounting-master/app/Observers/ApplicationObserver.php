<?php

namespace App\Observers;

use App\Application;

class ApplicationObserver
{
    /**
     * Handle the application "created" event.
     *
     * @param  \App\Application  $application
     * @return void
     */
    public function created(Application $application)
    {
        //
    }

    /**
     * Handle the application "updated" event.
     *
     * @param  \App\Application  $application
     * @return void
     */
    public function updated(Application $application)
    {
        //
    }

    /**
     * Handle the application "deleted" event.
     *
     * @param  \App\Application  $application
     * @return void
     */
    public function deleted(Application $application)
    {
        $application->services()->each(function ($service){
            $service->delete();
        });
    }

    /**
     * Handle the application "restored" event.
     *
     * @param  \App\Application  $application
     * @return void
     */
    public function restored(Application $application)
    {
        //
    }

    /**
     * Handle the application "force deleted" event.
     *
     * @param  \App\Application  $application
     * @return void
     */
    public function forceDeleted(Application $application)
    {
        //
    }
}
