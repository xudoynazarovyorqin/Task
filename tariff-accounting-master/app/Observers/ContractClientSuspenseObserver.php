<?php

namespace App\Observers;

use App\ApplicationPart;
use App\ContractClientSuspense;
use Carbon\Carbon;

class ContractClientSuspenseObserver
{
    public function created(ContractClientSuspense $contractClientSuspense)
    {
        if( $contract_client = $contractClientSuspense->contract_client ) {
            if( $application = $contract_client->application ) {
                $application_part = ApplicationPart::where('application_id', $application->id)
                    ->whereDate('start_date','=',Carbon::parse($contractClientSuspense->from_date)->toDateString())
                    ->whereDate('stop_date','=',Carbon::parse($contractClientSuspense->to_date)->toDateString())
                    ->where('amount', 0)
                    ->where('paid', 0)
                    ->where('status', ApplicationPart::INACTIVE)
                    ->first();

                if( !$application_part ) {
                    $application_part = ApplicationPart::create([
                        'application_id'    => $application->id,
                        'start_date'        => $contractClientSuspense->from_date,
                        'stop_date'         => $contractClientSuspense->to_date,
                        'amount'            => 0,
                        'paid'              => 0,
                        'status'            => ApplicationPart::INACTIVE,
                    ]);
                }
            }
        }
    }

    public function updated(ContractClientSuspense $contractClientSuspense)
    {
        //
    }

    public function deleted(ContractClientSuspense $contractClientSuspense)
    {
        if( $contract_client = $contractClientSuspense->contract_client ) {
            if( $application = $contract_client->application ) {
                $application_part = ApplicationPart::where('application_id', $application->id)
                    ->whereDate('start_date','=',Carbon::parse($contractClientSuspense->from_date)->toDateString())
                    ->whereDate('stop_date','=',Carbon::parse($contractClientSuspense->to_date)->toDateString())
                    ->where('amount', 0)
                    ->where('paid', 0)
                    ->where('status', ApplicationPart::INACTIVE)
                    ->first();

                if( $application_part ) {
                    $application_part->delete();
                }
            }
        }
    }

    public function restored(ContractClientSuspense $contractClientSuspense)
    {
        //
    }

    public function forceDeleted(ContractClientSuspense $contractClientSuspense)
    {
        //
    }
}
