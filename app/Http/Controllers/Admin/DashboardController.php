<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $total_users = DB::table('users')
            ->where('role', 'user')
            ->count();

        $total_trips = DB::table('trips')->count();

        $total_bookings = DB::table('bookings')->count();

        $total_revenue = DB::table('bookings')
            ->where('payment_status', 'paid')
            ->sum('total_price');

        $monthly_stats = collect();
        $monthly_labels = collect();
        $monthly_bookings = collect();

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->startOfMonth()->subMonths($i);

            $count = DB::table('bookings')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();

            $monthly_labels->push($month->translatedFormat('M Y'));
            $monthly_bookings->push($count);

            $monthly_stats->push([
                'month' => $month->translatedFormat('M Y'),
                'total' => $count,
            ]);
        }

        $category_stats = DB::table('destinations')
            ->selectRaw("
                COALESCE(NULLIF(category, ''), 'Lainnya') AS category,
                COUNT(*) AS total
            ")
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        $recent_bookings = DB::table('bookings')
            ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
            ->leftJoin(
                'destinations',
                'bookings.destination_id',
                '=',
                'destinations.id'
            )
            ->select(
                'bookings.id',
                'bookings.total_people',
                'bookings.total_price',
                'bookings.payment_status',
                'bookings.trip_status',
                'bookings.created_at',
                'users.name as user_name',
                'destinations.title as destination_title'
            )
            ->orderByDesc('bookings.created_at')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'total_users',
            'total_trips',
            'total_bookings',
            'total_revenue',
            'monthly_stats',
            'monthly_labels',
            'monthly_bookings',
            'category_stats',
            'recent_bookings'
        ));
    }
}
