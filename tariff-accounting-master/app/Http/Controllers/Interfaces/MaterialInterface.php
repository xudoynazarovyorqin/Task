<?php

namespace App\Http\Controllers\Interfaces;

use App\Http\Requests\MaterialRequest;
use Illuminate\Http\Request;

interface MaterialInterface
{
    public function index();    

    public function show($id);

    public function store(MaterialRequest $request);

    public function update(MaterialRequest $request, $id);

    public function destroy($id);
}
