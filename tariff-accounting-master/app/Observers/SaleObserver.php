<?php

namespace App\Observers;

use App\Sale;
use App\SaleCreatedInfo;
use App\SaleHistory;
use App\SaleNotEnoughMaterial;
use Illuminate\Support\Facades\DB;

class SaleObserver
{

    public function created(Sale $sale)
    {
        SaleHistory::create([
            'sale_id' => $sale->id,
            'comment' => 'Sale created',
            'level_id'=> $sale->level_id,
            'user_id' => auth()->user()->id,
        ]);

        SaleCreatedInfo::create([
            'sale_id' => $sale->id,
            'user_id' => auth()->user()->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('user-agent'),
            'accept_language' => request()->server('HTTP_ACCEPT_LANGUAGE'),
        ]);
    }

    public function updated(Sale $sale)
    {
        //
    }

    public function deleted(Sale $sale)
    {
        foreach ($sale->products as $product){
            $product->delete();
        }
    }

    public function restored(Sale $sale)
    {
        //
    }
    public function forceDeleted(Sale $sale)
    {
        //
    }
}
