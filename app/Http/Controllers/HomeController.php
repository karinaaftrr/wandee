<?php

namespace App\Http\Controllers;

use App\Models\Destination;

class HomeController extends Controller
{
    public function index()
    {
        $destinations = Destination::all();

        return view('landing_page', compact('destinations'));
    }
}
