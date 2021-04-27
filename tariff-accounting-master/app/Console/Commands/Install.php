<?php

namespace App\Console\Commands;

use App\Role;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crm:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install default user and permissions';

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
        if($this->confirm('Do you want to refresh all migrations ? (yes|no)[no]',true)) {
            Artisan::call('migrate:refresh');
        }
        /**
         * Seeders.
         */
        if($this->confirm('Do you want to create default measurements? (yes|no)[no]',true)) {
            Artisan::call('db:seed --class=MeasurementTableSeeder');
        }
        if($this->confirm('Do you want to create default statues ? (yes|no)[no]',true)) {
            Artisan::call('db:seed --class=StateSeeder');
        }
        if($this->confirm('Do you want to create default payment types ? (yes|no)[no]',true)) {
            Artisan::call('db:seed --class=PaymentTypeSeeder');
        }
//        if($this->confirm('Do ..\ want to create default levels ? (yes|no)[no]',true)) {
//            Artisan::call('db:seed --class=LevelTableSeeder');
//        }
//        if($this->confirm('Do you want to create default clients ? (yes|no)[no]',true)) {
//            Artisan::call('db:seed --class=ClientSeeder');
//        }
//        if($this->confirm('Do you want to create default priorities ? (yes|no)[no]',true)) {
//            Artisan::call('db:seed --class=PrioritySeeder');
//        }
        if($this->confirm('Do you want to create default permissions ? (yes|no)[no]',true)) {
            Artisan::call('db:seed --class=PermissionSeeder');
        }
        if($this->confirm('Do you want to create default currencies ? (yes|no)[no]',true)) {
            Artisan::call('db:seed --class=CurrencySeeder');
        }

        $role = null;

        if($this->confirm('Do you want to create a default role? (yes|no)[no]',true)) {
            $role = Role::firstOrCreate([
                'name' => "Admin role",
                'slug' => 'admin'
            ]);
            $role->permissions()->sync(DB::table('permissions')->pluck('id')->toArray());
        }

        if($this->confirm('Do you want to create a default system admin ? (yes|no)[no]',true)) {
            User::firstOrCreate([
                'name'          => 'GoMaxAdmin',
                'first_name'    => "Adminstration",
                'surname'       => "",
                'patronymic'    => "",
                'phone'         => "+998971234567",
                'password'      => bcrypt('123456'),
                'verified'      => User::VERIFIED,
                'role_id'       => ($role) ? $role->id : ''
            ]);
        }

        $this->info('Successfully installed. Thank you. We will wait your feedback.');
    }
}
