<?php

namespace App\Http\Controllers\Interfaces;


use Illuminate\Http\Request;

interface UserInterface
{
    public function index();

//    public function search();

    public function show($id);

    public function store(Request $request);

    public function update(Request $request,$id);

    public function destroy($id);
}
