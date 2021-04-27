<?php

namespace App\Http\Controllers\Interfaces;

use App\Client;
use App\Http\Requests\ClientRequest;

interface ClientInterface
{
    public function index();

    public function show(Client $client);

    public function store(ClientRequest $request);

    public function update(ClientRequest $request, Client $client);

    public function destroy(Client $client);
}
