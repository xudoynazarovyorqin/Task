<?php


namespace App\Listeners\Money\Mudofa;
use App\ApplicationPart;
use App\ContractClient;
use App\Events\Models\CreatePaymentEvent;
use App\Events\Money\Mudofa\DistributionMoneyEvent;
use Illuminate\Support\Facades\Log;
use App\Application;

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
        if ($application = $event->getApplication()){
            if( $contract_client = $application->contract_client ) {

                // oldin tanlangan item laga pul tolash
//                $items = $event->getItems();
//                if ($items && count($items)){
//                    foreach ($items as $item){
//                        $paying_sum = ($contract_client->remainder > $item['paying_amount']) ? $item['paying_amount'] : $contract_client->remainder;
//
//                        if( $paying_sum > 0 ) {
//                            $event = new CreatePaymentEvent();
//                            $event->setAmount($paying_sum);
//                            $event->setPaymentableId($item['paymentable_id']);
//                            $event->setPaymentableType($item['paymentable_type']);
//                            $event->setSourceableId($contract_client->id);
//                            $event->setSourceableType(ContractClient::TABLE_NAME);
//                            event($event);
//
//                            // dogovor balansidan ayirib qoyish
//                            $contract_client->remainder -= $paying_sum;
//                        }
//                    }
//                }

                // keyin bir boshidan yana korib chiqish
                $application_parts = ApplicationPart::where('application_id', $application->id)
                    ->whereRaw('amount - paid > 0')
                    ->where('status', ApplicationPart::ACTIVE)
                    ->orderBy('start_date', 'ASC')
                    ->orderBy('id', 'ASC')
                    ->get();

                foreach ($application_parts as $item){
                    $not_paid = $item->amount - $item->paid;
                    $paying_sum = ($contract_client->remainder > $not_paid) ? $not_paid : $contract_client->remainder;

                    if( $paying_sum > 0 ) {
                        $event = new CreatePaymentEvent();
                        $event->setAmount($paying_sum);
                        $event->setPaymentableId($item->id);
                        $event->setPaymentableType(ApplicationPart::TABLE_NAME);
                        $event->setSourceableId($contract_client->id);
                        $event->setSourceableType(ContractClient::TABLE_NAME);
                        event($event);

                        // dogovor balansidan ayirib qoyish
                        $contract_client->remainder -= $paying_sum;
                    }
                    else {
                        break;
                    }
                }

                // dogovor balansidan ayirgandan keyin soxranit qilib qoyish
                $contract_client->update();
            }
        }
    }
}
