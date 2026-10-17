<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $destinations = Destination::orderByDesc('rating')->get();

        return view('landing_page', compact('destinations'));
    }
}
