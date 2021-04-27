<?php

namespace App\Http\Controllers\Interfaces;

use Illuminate\Http\Request;

interface BuyInterface
{
    public function index();    

    public function show($id);

    public function store(Request $request);

    public function update(Request $request, $id);

    public function destroy($id);
}
