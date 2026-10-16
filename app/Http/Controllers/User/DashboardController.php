<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
     public function index()
    {
        $destinations = DB::table('destinations')
            ->orderByDesc('rating')
            ->get();

        $favoriteDestinationIds = DB::table('favorites')
            ->where('user_id', auth()->id())
            ->pluck('destination_id')
            ->toArray();

        $ratingStats = DB::table('reviews')
            ->select(
                'destination_id',
                DB::raw('AVG(rating) as avg_rating'),
                DB::raw('COUNT(*) as total_reviews')
            )
            ->groupBy('destination_id')
            ->get()
            ->keyBy('destination_id');

        return view('user.dashboard', compact(
            'destinations',
            'favoriteDestinationIds',
            'ratingStats'
        ));
    }

    public function toggleFavorite(Request $request)
    {
        $request->validate([
            'destination_id' => ['required', 'integer', 'exists:destinations,id'],
        ]);

        $userId = auth()->id();
        $destinationId = $request->destination_id;

        $favorite = DB::table('favorites')
            ->where('user_id', $userId)
            ->where('destination_id', $destinationId)
            ->first();

        if ($favorite) {
            DB::table('favorites')
                ->where('user_id', $userId)
                ->where('destination_id', $destinationId)
                ->delete();

            return back()->with('success', 'Destinasi dihapus dari favorit.');
        }

        DB::table('favorites')->insert([
            'user_id' => $userId,
            'destination_id' => $destinationId,
            'created_at' => now(),
        ]);

        return back()->with('success', 'Destinasi ditambahkan ke favorit.');
    }
}
