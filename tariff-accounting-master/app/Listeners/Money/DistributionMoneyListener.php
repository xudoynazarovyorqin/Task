<?php

namespace App\Listeners\Money;

use App\Buy;
use App\BuyReadyProduct;
use App\Client;
use App\Events\Models\CreatePaymentEvent;
use App\Events\Money\DistributionMoneyEvent;
use App\Order;
use App\Provider;
use App\SaleReadyProduct;
use App\Transaction;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class DistributionMoneyListener
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

    public function handle(DistributionMoneyEvent $event)
    {
        if ($transaction = $event->getTransaction()){
            if ($transaction->transactionable_type == Client::TABLE_NAME || $transaction->transactionable_type == Provider::TABLE_NAME){
                $items = $event->getItems();
                $rem = $transaction->real_amount;
                if ($items && count($items)){
                    foreach ($items as $item){
                        $event = new CreatePaymentEvent();
                        $event->setAmount($item['paying_amount']);
                        $event->setPaymentableId($item['paymentable_id']);
                        $event->setPaymentableType($item['paymentable_type']);
                        $event->setSourceableId($transaction->id);
                        $event->setSourceableType(Transaction::TABLE_NAME);
                        event($event);
                        $rem -=$item['paying_amount'];
                    }
                }

                if ($rem > 0){
                    Log::info(setting('auto_pay',false));
                    if (boolval(setting('auto_pay',false)) === true){
                        if ($transaction->transactionable_type == Client::TABLE_NAME){
                            $sale_ready_products = SaleReadyProduct::where('client_id',$transaction->transactionable_id)->paid(false)->oldest()->get();
                            foreach ($sale_ready_products as $sale_ready_product){
                                $un_paid = $sale_ready_product->total_price  - $sale_ready_product->paid_price;
                                if ($un_paid > 0){
                                    $paying_amount = ($un_paid >= $rem) ? $rem : $un_paid;
                                    $event = new CreatePaymentEvent();
                                    $event->setAmount($paying_amount);
                                    $event->setPaymentableId($sale_ready_product->id);
                                    $event->setPaymentableType(SaleReadyProduct::TABLE_NAME);
                                    $event->setSourceableId($transaction->id);
                                    $event->setSourceableType(Transaction::TABLE_NAME);
                                    event($event);
                                    $rem -=$paying_amount;
                                    if ($rem <= 0) break;
                                }
                            }
                            if ($rem > 0){
                                $orders = Order::where('client_id',$transaction->transactionable_id)->paid(false)->oldest()->get();
                                foreach ($orders as $order){
                                    $un_paid = $order->amount  - $order->paid;
                                    if ($un_paid > 0){
                                        $paying_amount = ($un_paid >= $rem) ? $rem : $un_paid;
                                        $event = new CreatePaymentEvent();
                                        $event->setAmount($paying_amount);
                                        $event->setPaymentableId($order->id);
                                        $event->setPaymentableType(Order::TABLE_NAME);
                                        $event->setSourceableId($transaction->id);
                                        $event->setSourceableType(Transaction::TABLE_NAME);
                                        event($event);
                                        $rem -=$paying_amount;
                                        if ($rem <= 0) break;
                                    }
                                }
                            }
                        }elseif ($transaction->transactionable_type == Provider::TABLE_NAME){
                            $buys = Buy::where('provider_id',$transaction->transactionable_id)->paid(false)->oldest()->get();
                            foreach ($buys as $buy){
                                $un_paid = $buy->total_price  - $buy->paid_price;
                                if ($un_paid > 0){
                                    $paying_amount = ($un_paid >= $rem) ? $rem : $un_paid;
                                    $event = new CreatePaymentEvent();
                                    $event->setAmount($paying_amount);
                                    $event->setPaymentableId($buy->id);
                                    $event->setPaymentableType(Buy::TABLE_NAME);
                                    $event->setSourceableId($transaction->id);
                                    $event->setSourceableType(Transaction::TABLE_NAME);
                                    event($event);
                                    $rem -=$paying_amount;
                                    if ($rem <= 0) break;
                                }
                            }
                            if ($rem > 0){
                                $buys = BuyReadyProduct::where('provider_id',$transaction->transactionable_id)->paid(false)->oldest()->get();
                                foreach ($buys as $buy){
                                    $un_paid = $buy->amount  - $buy->paid;
                                    if ($un_paid > 0){
                                        $paying_amount = ($un_paid >= $rem) ? $rem : $un_paid;
                                        $event = new CreatePaymentEvent();
                                        $event->setAmount($paying_amount);
                                        $event->setPaymentableId($buy->id);
                                        $event->setPaymentableType(BuyReadyProduct::TABLE_NAME);
                                        $event->setSourceableId($transaction->id);
                                        $event->setSourceableType(Transaction::TABLE_NAME);
                                        event($event);
                                        $rem -=$paying_amount;
                                        if ($rem <= 0) break;
                                    }
                                }
                            }
                        }
                    }
                }

                /**
                 * Set amount to client or provider balance if remainder big from oh.
                 */
                if ($rem > 0){
                    $event = new CreatePaymentEvent();
                    $event->setAmount($rem);
                    $event->setPaymentableId($transaction->transactionable_id);
                    $event->setPaymentableType($transaction->transactionable_type);
                    $event->setSourceableId($transaction->id);
                    $event->setSourceableType(Transaction::TABLE_NAME);
                    event($event);
                }
            }
        }
    }
}
