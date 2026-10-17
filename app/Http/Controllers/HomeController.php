<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $destinations = Destination::orderByDesc('rating')->get();
        $favoriteDestinationIds = [];

        if (Auth::check()) {
            $favoriteDestinationIds = Favorite::where('user_id', Auth::id())
                ->pluck('destination_id')
                ->toArray();
        }

        return view('landing_page', compact('destinations', 'favoriteDestinationIds'));
    }
}
