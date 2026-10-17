<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $reviews = DB::table('reviews')
            ->leftJoin('users', 'reviews.user_id', '=', 'users.id')
            ->leftJoin('destinations', 'reviews.destination_id', '=', 'destinations.id')
            ->select(
                'reviews.*',
                'users.name as user_name',
                'destinations.title as destination_title'
            )
            ->orderByDesc('reviews.created_at')
            ->get();

        return view('admin.manage_reviews', compact('user', 'reviews'));
    }

    public function destroy($id)
    {
        $review = DB::table('reviews')->where('id', $id)->first();

        if ($review) {
            DB::table('reviews')->where('id', $id)->delete();

            $avgRating = DB::table('reviews')
                ->where('destination_id', $review->destination_id)
                ->avg('rating');

            if ($avgRating !== null) {
                DB::table('destinations')
                    ->where('id', $review->destination_id)
                    ->update(['rating' => round($avgRating, 1)]);
            }
        }

        return redirect()->route('admin.manage_reviews')->with('success', 'Ulasan berhasil dihapus.');
    }
}
