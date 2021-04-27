<?php

namespace App\Observers;

use App\Currency;

class CurrencyObserver
{
    public function created(Currency $currency)
    {
        if ($currency['reverse'] == true)
        {
            $currency['rate'] = round(1 / $currency['reversed_rate'],8);
            $currency->update();
        }else{
            $currency['reversed_rate'] = round(1 / $currency['rate'],8);
            $currency->update();
        }
    }

    public function updated(Currency $currency)
    {
        //
    }

    public function deleted(Currency $currency)
    {
        //
    }

    public function restored(Currency $currency)
    {
        //
    }

    public function forceDeleted(Currency $currency)
    {
        //
    }
}
