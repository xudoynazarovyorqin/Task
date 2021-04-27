<?php

namespace App\Observers;

use App\Reservation;

class ReservationObserver
{
    public function created(Reservation $reservation)
    {
        /**
         * Here source warehouse_material or warehouse_product
         */
        if ($source = $reservation->sourceable){
            $source->booked += $reservation->quantity;
            $source->update();
        }
    }

    public function updated(Reservation $reservation)
    {
        //
    }

    public function deleted(Reservation $reservation)
    {
        /**
         * Here source warehouse_material or warehouse_product
         */
        if ($source = $reservation->sourceable){
            $source->booked -= $reservation->quantity;
            $source->update();
        }
    }

    public function restored(Reservation $reservation)
    {
        //
    }

    public function forceDeleted(Reservation $reservation)
    {
        //
    }
}
