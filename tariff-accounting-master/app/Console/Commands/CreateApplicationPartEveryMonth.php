<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\ApplicationController;
use Illuminate\Console\Command;

class CreateApplicationPartEveryMonth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mudofa:createApplicationPartEveryMonth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Har oyni boshida ApplicationPart qoshish';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ApplicationController::createApplicationPartEveryMonth();
    }
}
