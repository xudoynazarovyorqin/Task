<?php

namespace App\Exports;

use App\Country;
use Maatwebsite\Excel\Concerns\FromCollection;

class ClientsExport implements FromCollection
{
	// protected $invoices;

 //    public function __construct(array $invoices)
 //    {
 //        $this->invoices = $invoices;
 //    }

 //    public function array(): array
 //    {
 //        return $this->invoices;
 //    }
 		

 	public function collection()
    {
        return Country::all();
    }
}
