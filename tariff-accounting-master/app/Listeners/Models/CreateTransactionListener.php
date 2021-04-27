<?php

namespace App\Listeners\Models;

use App\Events\Models\CreateTransactionEvent;
use App\Events\Money\DistributionMoneyEvent;
use App\Transaction;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateTransactionListener
{
    public function __construct()
    {
        //
    }

    public function handle(CreateTransactionEvent $event)
    {
       $transaction = Transaction::create([
           'transactionable_type'   => $event->getTransactionableType(),
           'transactionable_id'     => $event->getTransactionableId(),
           'contractable_type'      => $event->getContractableType(),
           'contractable_id'        => $event->getContractableId(),
           'payment_type_id'        => $event->getPaymentTypeId(),
           'score_id'               => $event->getScoreId(),
           'amount'                 => $event->getAmount(),
           'currency_id'            => $event->getCurrencyId(),
           'rate'                   => $event->getRate(),
           'datetime'               => $event->getDatetime(),
           'comment'                => $event->getComment(),
       ]);

       $eventDist = new DistributionMoneyEvent($transaction);
       $eventDist->setItems($event->getRelatedItems());
       event($eventDist);
    }
}
