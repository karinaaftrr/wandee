<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $filter = $request->query('filter', 'all');

        $query = DB::table('payments')
            ->leftJoin('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
            ->leftJoin('destinations', 'bookings.destination_id', '=', 'destinations.id')
            ->select(
                'payments.*',
                'bookings.total_people',
                'bookings.total_price',
                'bookings.payment_status as booking_payment_status',
                'bookings.trip_status',
                'users.name as user_name',
                'destinations.title as destination_title'
            );

        if ($filter !== 'all') {
            $query->where('payments.payment_status', $filter);
        }

        $payments = $query->orderByDesc('payments.created_at')->get();

        return view('admin.manage_payments', compact('user', 'payments', 'filter'));
    }

    public function updateStatus(Request $request)
    {
        $paymentId = (int) $request->input('payment_id', 0);
        $status = $request->input('status', '');
        $rejectionReason = trim($request->input('rejection_reason', ''));

        $validStatuses = ['verified', 'rejected'];

        if ($paymentId && in_array($status, $validStatuses, true)) {
            $payment = DB::table('payments')->where('id', $paymentId)->first();

            if ($payment) {
                $booking = DB::table('bookings')->where('id', $payment->booking_id)->first();

                if ($booking && ($booking->trip_status ?? '') === 'cancelled') {
                    return redirect()->route('admin.manage_payments');
                }

                DB::table('payments')->where('id', $paymentId)->update([
                    'payment_status' => $status,
                    'rejection_reason' => $rejectionReason,
                    'updated_at' => now(),
                ]);

                DB::table('bookings')->where('id', $payment->booking_id)->update([
                    'payment_status' => $status === 'verified' ? 'paid' : 'pending',
                ]);

                if ($status === 'verified') {
                    DB::table('bookings')->where('id', $payment->booking_id)->update([
                        'trip_status' => 'ongoing',
                    ]);
                }

                if ($status === 'rejected' && $booking) {
                    DB::table('destinations')
                        ->where('id', $booking->destination_id)
                        ->increment('quota', $booking->total_people);
                }
            }
        }

        return redirect()->route('admin.manage_payments');
    }

    public function tripComplete(Request $request)
    {
        $bookingId = (int) $request->input('booking_id', 0);

        if ($bookingId > 0) {
            DB::table('bookings')->where('id', $bookingId)->update([
                'trip_status' => 'completed',
            ]);
        }

        return redirect()->route('admin.manage_payments');
    }
}
