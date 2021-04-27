<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    const WEEKLY = 'weekly';
    const MONTHLY = 'monthly';
    const YEARLY = 'yearly';
    const PERIOD = 'period';
    const EXCEL_DATE_FORMAT = "d.m.Y H:i";
    const DATE_FORMAT = 'Y/m/d';

    const ELEMENT_DATE_TIME_FORMAT = 'Y-m-d H:i';
    const ELEMENT_DATE_FORMAT = 'Y-m-d';
    const DEFAULT_PER_PAGE_PAGINATION = 10000;
}
