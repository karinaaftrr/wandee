<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return view('admin.profile', compact('user'));
    }
}
