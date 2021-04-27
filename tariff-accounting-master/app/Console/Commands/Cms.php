<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Cms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crm:model {model}';

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

        $model = ucfirst($this->argument('model'));

        if($this->confirm('Do you want to create a model ? (yes|no)[no]',true)) {
            Artisan::call("make:model " . $model . " -m");
        }
        if($this->confirm('Do you want to create Controller --api ? (yes|no)[no]',true)) {
            Artisan::call("make:controller Api/" . $model . "Controller --api");
        }
        if($this->confirm('Do you want to create Observer ? (yes|no)[no]',true)) {
            Artisan::call("make:observer " . $model . "Observer --model=" . $model);
        }
        if($this->confirm('Do you want to create a request ? (yes|no)[no]',true)) {
            Artisan::call("make:request " . $model . "Request");
        }
        if($this->confirm('Do you want to create Resource ? (yes|no)[no]',true)) {
            Artisan::call("make:resource " . $model);
        }
        if($this->confirm('Do you want to create Resource Collection ? (yes|no)[no]',true)) {
            Artisan::call("make:resource " . $model . "Collection");
        }
        if($this->confirm('Do you want to create Relation Resource ? (yes|no)[no]',true)) {
            Artisan::call("make:resource Relation/" . $model);
        }
        if($this->confirm('Do you want to create Relation Resource Collection ? (yes|no)[no]',true)) {
            Artisan::call("make:resource Relation/" . $model . "Collection");
        }
        if($this->confirm('Do you want to create Inventory Resource ? (yes|no)[no]',true)) {
            Artisan::call("make:resource Inventory/" . $model);
        }
        if($this->confirm('Do you want to create Inventory Resource Collection ? (yes|no)[no]',true)) {
            Artisan::call("make:resource Inventory/" . $model . "Collection");
        }

        $this->info("Insert " . $model . "Observer in the boot section of AppServiceProvider");
    }
}
