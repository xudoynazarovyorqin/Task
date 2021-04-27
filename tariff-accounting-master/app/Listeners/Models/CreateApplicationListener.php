<?php

namespace App\Listeners\Models;

use App\Application;
use App\ApplicationService;
use App\Events\Models\CreateApplicationEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateApplicationListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CreateApplicationEvent  $event
     * @return void
     */
    public function handle(CreateApplicationEvent $event)
    {
        /**
         * Create application
         */
        $application = Application::create([
            'number'            => $event->getNumber(),
            'datetime'          => $event->getDatetime(),
            'client_id'         => $event->getClientId(),
            'contract_client_id'=> $event->getContractClientId(),
            'status_id'         => $event->getStatusId(),
            'console_number'    => $event->getConsoleNumber(),
            'object_name'       => $event->getObjectName(),
            'district_id'       => $event->getDistrictId(),
            'quarter_id'        => $event->getQuarterId(),
            'object_street'     => $event->getObjectStreet(),
            'object_home'       => $event->getObjectHome(),
            'object_corps'      => $event->getObjectCorps(),
            'object_flat'       => $event->getObjectFlat(),
            'amount'            => 0,
        ]);

        /**
         * Create application services
         */
        if ($application_services = $event->getServices()){
            if (is_array($application_services)){
                foreach ($application_services as $item){
                    $application_service = ApplicationService::create([
                        'application_id'    => $application->id,
                        'service_id'        => $item['service_id'],
                        'price'             => $item['price'],
                    ]);
                }
            }
        }
    }
}
