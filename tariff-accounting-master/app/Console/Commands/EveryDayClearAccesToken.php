<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class EveryDayClearAccesToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'everyday:cleartoken';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Every day to clear all Users access tokens';

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
         Log::info('Commanda ishladi!!!');
    }
}
