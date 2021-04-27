<?php

namespace App\Observers;

use App\Client;
use App\ContractClient;
use App\ContractProvider;
use App\Score;
use App\Transaction;
use Illuminate\Support\Facades\Log;

class TransactionObserver
{
    public function creating(Transaction $transaction)
    {
        $transaction->user_id = auth()->user()->id;
        $transaction->debit = $transaction->transactionable_type == Client::TABLE_NAME ? 1 : -1;
        $transaction->real_amount = $transaction->amount * $transaction->rate;
    }

    public function created(Transaction $transaction)
    {
        if ($score = $transaction->score){
            if ($transaction->debit == 1){
                $score->incoming +=$transaction->amount;
            }else{
                $score->outgoing +=$transaction->amount;
            }
            $score->update();
        }

        if ($contract = $transaction->contractable){
            $contract->paid +=$transaction->real_amount;
            $contract->update();
        }
    }

    public function updating(Transaction $transaction){
        /**
         * Control score balance for transaction.
         */
        /**
         * TODO:: If score changed error working
         */
        $transaction->real_amount = $transaction->amount * $transaction->rate;

        $original = $transaction->getOriginal();

        if($old_score = Score::find($original['score_id'])){
            if ($transaction->debit == 1){
                $old_score->incoming -=floatval($original['amount']);
            }else{
                $old_score->outgoing -=floatval($original['amount']);
            }
            $old_score->update();
        }

        if ($original['contractable_type'] == ContractProvider::TABLE_NAME){
            if ($old_contract = ContractProvider::find($original['contractable_id'])){
                $old_contract->paid -=floatval($original['real_amount']);
                $old_contract->update();
            }
        }elseif ($original['contractable_type'] == ContractClient::TABLE_NAME){
            if ($old_contract = ContractClient::find($original['contractable_id'])){
                $old_contract->paid -=floatval($original['real_amount']);
                $old_contract->update();
            }
        }

        if ($score = Score::find($transaction->score_id)){
            if ($transaction->debit == 1){
                $score->incoming +=$transaction->amount;
            }else{
                $score->outgoing +=$transaction->amount;
            }
            $score->update();
        }

        if ($transaction->contractable_type == ContractProvider::TABLE_NAME){
            if ($contract = ContractProvider::find($transaction->contractable_id)){
                $contract->paid +=floatval($original['real_amount']);
                $contract->update();
            }
        }elseif ($transaction->contractable_type == ContractClient::TABLE_NAME){
            if ($contract = ContractClient::find($transaction->contractable_id)){
                $contract->paid +=floatval($original['real_amount']);
                $contract->update();
            }
        }
    }

    public function updated(Transaction $transaction)
    {
        //
    }

    public function deleted(Transaction $transaction)
    {
        /**
         * Remove transaction amount from score.
         */
        if ($score = $transaction->score){
            if ($transaction->debit == 1){
                $score->incoming -=$transaction->amount;
            }else{
                $score->outgoing -=$transaction->amount;
            }
            $score->update();
        }

        if ($contract = $transaction->contractable){
            $contract->paid -=$transaction->real_amount;
            $contract->update();
        }
        /**
         * Delete transaction payments
         */
        $transaction->payments()->each(function ($item){
           $item->delete();
        });
    }

    public function restored(Transaction $transaction)
    {
        //
    }

    public function forceDeleted(Transaction $transaction)
    {
        //
    }
}
