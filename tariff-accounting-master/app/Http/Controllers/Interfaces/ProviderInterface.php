<?php

namespace App\Http\Controllers\Interfaces;

use App\Http\Requests\ProviderRequest;
use App\Provider;

interface ProviderInterface
{
    public function index();

    public function show(Provider $provider);

    public function store(ProviderRequest $request);

    public function update(ProviderRequest $request, Provider $provider);

    public function destroy(Provider $provider);
}
