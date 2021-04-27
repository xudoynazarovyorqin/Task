<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\ApplicationController;
use Illuminate\Console\Command;

class SendSmsToTelegramBotAboutTopUpBalance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mudofa:sendSmsToTelegramBotAboutTopUpBalance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        ApplicationController::sendSmsToTelegramBotAboutTopUpBalance();
    }
}
