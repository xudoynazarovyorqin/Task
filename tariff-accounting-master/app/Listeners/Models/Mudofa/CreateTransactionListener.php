<?php


namespace App\Listeners\Models\Mudofa;

use App\Events\Models\Mudofa\CreateTransactionEvent;
use App\Http\Controllers\Api\Mudofa\TransactionController;
use Goodoneuz\PayUz\Models\Transaction;

class CreateTransactionListener
{
    public function __construct()
    {
        //
    }

    public function handle(CreateTransactionEvent $event)
    {
        $transaction = Transaction::create([
            'payment_system'        => $event->getPaymentSystem(),
            'system_transaction_id' => $event->getSystemTransactionId(),
            'amount'                => $event->getAmount(),
            'currency_code'         => $event->getCurrencyCode(),
            'state'                 => $event->getState(),
            'updated_time'          => $event->getUpdatedTime(),
            'comment'               => $event->getComment(),
            'detail'                => $event->getDetail(),
            'transactionable_type'  => $event->getTransactionableType(),
            'transactionable_id'    => $event->getTransactionableId(),
        ]);

        TransactionController::createPaymentFromSystemTransaction($transaction, $event->getRelatedItems());
    }
}
