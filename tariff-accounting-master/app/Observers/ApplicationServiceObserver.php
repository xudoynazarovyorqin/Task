<?php

namespace App\Observers;

use App\ApplicationService;

class ApplicationServiceObserver
{
    /**
     * Handle the application service "created" event.
     *
     * @param  \App\ApplicationService  $applicationService
     * @return void
     */
    public function created(ApplicationService $applicationService)
    {
        if ($application = $applicationService->application){
            $application->amount += $applicationService->price;
            $application->update();
        }
    }

    /**
     * Handle the application service "updated" event.
     *
     * @param  \App\ApplicationService  $applicationService
     * @return void
     */
    public function updated(ApplicationService $applicationService)
    {
        //
    }

    /**
     * Handle the application service "deleted" event.
     *
     * @param  \App\ApplicationService  $applicationService
     * @return void
     */
    public function deleted(ApplicationService $applicationService)
    {
        if ($application = $applicationService->application){
            $application->amount -= $applicationService->price;
            $application->update();
        }
    }

    /**
     * Handle the application service "restored" event.
     *
     * @param  \App\ApplicationService  $applicationService
     * @return void
     */
    public function restored(ApplicationService $applicationService)
    {
        //
    }

    /**
     * Handle the application service "force deleted" event.
     *
     * @param  \App\ApplicationService  $applicationService
     * @return void
     */
    public function forceDeleted(ApplicationService $applicationService)
    {
        //
    }
}
