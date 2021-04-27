<?php

namespace App\Listeners\Update;

use App\Events\Money\DistributionMoneyEvent;
use App\Events\Update\UpdateTransactionEvent;
use App\Transaction;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateTransactionListener
{
    public function __construct()
    {
        //
    }

    public function handle(UpdateTransactionEvent $event)
    {
        $transaction = Transaction::find($event->getId());

        $transaction->payments()->each(function ($item){
           $item->delete();
        });

        $transaction->update([
            'transactionable_id'     => $event->getTransactionableId(),
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
