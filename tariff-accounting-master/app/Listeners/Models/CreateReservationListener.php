<?php

namespace App\Listeners\Models;

use App\Events\Models\CreateReservationEvent;
use App\Reservation;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateReservationListener
{
    public function __construct()
    {
        //
    }

    public function handle(CreateReservationEvent $event)
    {
        $reservation = Reservation::create([
            Reservation::ABLE_TYPE => $event->getReservationableType(),
            Reservation::ABLE_ID => $event->getReservationableId(),
            Reservation::SOURCEABLE_TYPE => $event->getSourceableType(),
            Reservation::SOURCEABLE_ID => $event->getSourceableId(),
            'quantity'  => $event->getQuantity(),
            'issued'    => $event->getIssued()
        ]);
    }
}
