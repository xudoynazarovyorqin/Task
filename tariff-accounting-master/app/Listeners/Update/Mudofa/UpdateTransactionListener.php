<?php


namespace App\Listeners\Update\Mudofa;

use App\Events\Update\UpdateTransactionEvent;
use App\Http\Controllers\Api\Mudofa\TransactionController;
use Goodoneuz\PayUz\Models\Transaction;

class UpdateTransactionListener
{
    public function __construct()
    {
        //
    }

    public function handle(UpdateTransactionEvent $event)
    {
        $transaction = Transaction::find($event->getId());

        // update dan oldin eski payment lani qaytarish kere. Paymentla Transaction bilan boglanmagan. Payment ContractClient bilan bog'langan

        $transaction->update([
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

        // va boshqattan pullani tarqatish
        TransactionController::createPaymentFromSystemTransaction($transaction, $event->getRelatedItems());
    }
}
