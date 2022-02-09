<?php

namespace App\Http\Controllers;

class FrontendController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function apiDoc()
    {
        return view('api-doc');
    }
}
