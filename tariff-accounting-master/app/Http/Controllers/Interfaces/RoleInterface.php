<?php

namespace App\Http\Controllers\Interfaces;

use App\Http\Requests\RoleRequest;
use App\Role;
use Illuminate\Http\Request;

interface RoleInterface
{
    public function index();

    public function show(Role $role);

    public function store(RoleRequest $request);

    public function update(RoleRequest $request, Role $role);

    public function destroy(Role $role);
}
