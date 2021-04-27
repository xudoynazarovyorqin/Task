<?php

namespace App\Http\Controllers\Interfaces;

use App\Http\Requests\PermissionRequest;
use App\Permission;

interface PermissionInterface
{
    public function index();

    public function show(Permission $permission);

    public function store(PermissionRequest $request);

    public function update(PermissionRequest $request, Permission $permission);

    public function destroy(Permission $permission);
}
