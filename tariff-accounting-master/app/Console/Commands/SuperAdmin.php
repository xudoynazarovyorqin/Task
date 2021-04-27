<?php

namespace App\Console\Commands;

use App\Permission;
use App\Role;
use Illuminate\Console\Command;

class SuperAdmin extends Command
{
    protected $signature = 'crm:superadmin {role}';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        if ($role = Role::find($this->argument('role'))){
            $role->permissions()->sync(Permission::pluck('id')->toArray());
        }
    }
}
