<?php

namespace App\Listeners\Models;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use App\Events\Models\CreatePaymentEvent;
use App\Payment;
use App\Events\Models\CreateTransactionEvent;

class CreatePaymentEventListener
{
    public function __construct()
    {
        //
    }

    public function handle(CreatePaymentEvent $event)
    {
        Payment::create([
           'paymentable_type'   => $event->getPaymentableType(),
           'paymentable_id'     => $event->getPaymentableId(),
           'sourceable_type'    => $event->getSourceableType(),
           'sourceable_id'      => $event->getSourceableId(),
           'amount'             => $event->getAmount(),
        ]);
    }
}
