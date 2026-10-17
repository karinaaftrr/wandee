<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Destination;
use App\Models\Favorite;
use App\Models\Payment;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Dashboard / Jelajah User
     */
    public function index()
    {
        $destinations = Destination::orderByDesc('rating')->get();
        $user_id = Auth::id();

        $favoriteDestinationIds = Favorite::where('user_id', $user_id)
            ->pluck('destination_id')
            ->toArray();

        $ratingStats = Review::select(
                'destination_id',
                DB::raw('AVG(rating) as avg_rating'),
                DB::raw('COUNT(*) as total_reviews')
            )
            ->groupBy('destination_id')
            ->get()
            ->keyBy('destination_id');

        $name = Auth::user()->name;

        return view('user.dashboard', compact(
            'destinations',
            'favoriteDestinationIds',
            'ratingStats',
            'name'
        ));
    }

    /**
     * Detail Destinasi
     */
    public function detail($id = null)
    {
        $destinationId = $id ?? request('id');
        if (!$destinationId) {
            return redirect('/#destinations');
        }
        $dest = Destination::with(['reviews.user'])->findOrFail($destinationId);
        $user_id = Auth::id();

        $detailData = $this->prepareDestinationDetail($dest);
        $isFavorite = Favorite::where('user_id', $user_id)->where('destination_id', $destinationId)->exists();
        $reviews = $dest->reviews;

        return view('user.detail', array_merge([
            'dest' => $dest,
            'isFavorite' => $isFavorite,
            'reviews' => $reviews,
        ], $detailData));
    }

    /**
     * Form Pembayaran / Pilih Jumlah Orang & Metode
     */
    public function payment(Request $request, $id = null)
    {
        $destination_id = $id ?? $request->query('id');
        $destination = Destination::findOrFail($destination_id);

        $quantity = max(1, (int)$request->query('people', 1));

        if ($destination->quota < $quantity) {
            return redirect()->route('user.detail', $destination->id)
                ->with('booking_error', 'Kuota tidak mencukupi untuk jumlah peserta yang Anda pilih.');
        }

        $price = $this->priceToInt($destination->price);
        $total_price = $price * $quantity;

        return view('user.payment', compact('destination', 'quantity', 'price', 'total_price'));
    }

    /**
     * Inisialisasi Booking & Pembayaran
     */
    public function paymentInit(Request $request)
    {
        $request->validate([
            'destination_id' => 'required|exists:destinations,id',
            'total_people' => 'required|integer|min:1',
            'payment_method' => 'required|string',
        ]);

        $destination = Destination::findOrFail($request->destination_id);
        $quantity = (int)$request->total_people;

        if ($destination->quota < $quantity) {
            return redirect()->route('user.detail', $destination->id)
                ->with('booking_error', 'Kuota tidak mencukupi untuk jumlah peserta yang Anda pilih.');
        }

        $price = $this->priceToInt($destination->price);
        $total_price = $price * $quantity;

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'destination_id' => $destination->id,
            'total_people' => $quantity,
            'total_price' => $total_price,
            'payment_status' => 'pending',
            'trip_status' => 'new',
            'created_at' => now(),
        ]);

        // Kurangi kuota destinasi
        $destination->decrement('quota', $quantity);

        $payment = Payment::create([
            'booking_id' => $booking->id,
            'payment_method' => $request->payment_method,
            'payment_amount' => $total_price,
            'unique_code' => rand(100, 999),
            'payment_status' => 'waiting',
        ]);

        return redirect()->route('user.payment_detail', [
            'booking_id' => $booking->id,
            'payment_id' => $payment->id,
        ]);
    }

    /**
     * Halaman Instruksi Transfer & Upload Bukti
     */
    public function paymentDetail(Request $request)
    {
        $booking_id = $request->query('booking_id');
        $payment_id = $request->query('payment_id');

        $booking = Booking::with('destination')->where('id', $booking_id)->where('user_id', Auth::id())->firstOrFail();
        
        $payment = $payment_id
            ? Payment::where('id', $payment_id)->where('booking_id', $booking->id)->firstOrFail()
            : Payment::where('booking_id', $booking->id)->latest()->firstOrFail();

        $destination = $booking->destination;
        $price = $this->priceToInt($destination->price);
        $voucher_code = trim((string)($payment->unique_code ?? ''));
        $payment_total = (int)$payment->payment_amount;

        return view('user.payment_detail', compact(
            'booking',
            'payment',
            'destination',
            'price',
            'voucher_code',
            'payment_total'
        ));
    }

    /**
     * Upload Bukti Pembayaran
     */
    public function paymentUpload(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'payment_id' => 'required|exists:payments,id',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $booking = Booking::where('id', $request->booking_id)->where('user_id', Auth::id())->firstOrFail();
        $payment = Payment::where('id', $request->payment_id)->where('booking_id', $booking->id)->firstOrFail();

        $file = $request->file('payment_proof');
        $filename = time() . '_' . preg_replace('/[^A-Za-z0-9_\.\-]/', '_', $file->getClientOriginalName());

        $destinationPath = public_path('uploads/payments');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $file->move($destinationPath, $filename);

        $payment->update([
            'payment_proof' => $filename,
            'payment_status' => 'waiting',
        ]);

        return redirect()->route('user.payment_status', ['booking_id' => $booking->id]);
    }

    /**
     * Halaman Status Pembayaran
     */
    public function paymentStatus(Request $request)
    {
        $booking_id = $request->query('booking_id');
        $booking = Booking::with('destination')->where('id', $booking_id)->where('user_id', Auth::id())->firstOrFail();
        $payment = Payment::where('booking_id', $booking->id)->latest()->firstOrFail();

        $destination = $booking->destination;
        $voucher_code = trim((string)($payment->unique_code ?? ''));
        $payment_total = (int)$payment->payment_amount;

        return view('user.payment_status', compact(
            'booking',
            'payment',
            'destination',
            'voucher_code',
            'payment_total'
        ));
    }

    /**
     * Riwayat Perjalanan
     */
    public function riwayat()
    {
        $bookings = Booking::with(['destination', 'payment'])
            ->where('user_id', Auth::id())
            ->orderByDesc('id')
            ->get();

        return view('user.riwayat', compact('bookings'));
    }

    /**
     * Daftar Favorit
     */
    public function favorite()
    {
        $favorites = Favorite::with('destination')
            ->where('user_id', Auth::id())
            ->orderByDesc('id')
            ->get();

        return view('user.favorite', compact('favorites'));
    }

    /**
     * Toggle Favorit (Tambah / Hapus)
     */
    public function toggleFavorite(Request $request)
    {
        $request->validate([
            'destination_id' => 'required|integer|exists:destinations,id',
        ]);

        $userId = Auth::id();
        $destinationId = $request->destination_id;

        $favorite = Favorite::where('user_id', $userId)
            ->where('destination_id', $destinationId)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $message = 'Destinasi dihapus dari favorit.';
        } else {
            Favorite::create([
                'user_id' => $userId,
                'destination_id' => $destinationId,
                'created_at' => now(),
            ]);
            $message = 'Destinasi ditambahkan ke favorit.';
        }

        if ($request->wantsJson()) {
            return response()->json(['status' => 'success', 'message' => $message]);
        }

        return back()->with('success', $message);
    }

    /**
     * Halaman Ulasan
     */
    public function ulasan()
    {
        $userId = Auth::id();

        // Ambil trip yang sudah selesai dan pembayarannya terverifikasi
        $completedBookings = Booking::with('destination')
            ->where('user_id', $userId)
            ->where('trip_status', 'completed')
            ->get();

        $reviewedDestinationIds = Review::where('user_id', $userId)
            ->pluck('destination_id')
            ->toArray();

        $eligibleBookings = $completedBookings->filter(function ($booking) use ($reviewedDestinationIds) {
            return !in_array($booking->destination_id, $reviewedDestinationIds);
        })->values();

        $selectedBooking = $eligibleBookings->first();

        // Ulasan yang sudah pernah ditulis oleh user
        $myReviews = Review::with('destination')
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->get();

        return view('user.ulasan', compact('eligibleBookings', 'selectedBooking', 'myReviews'));
    }

    /**
     * Kirim Ulasan
     */
    public function submitReview(Request $request)
    {
        $request->validate([
            'destination_id' => 'required|exists:destinations,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:500',
            'review_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ], [
            'rating.required' => 'Pilih rating bintang 1-5 terlebih dahulu.',
            'review.required' => 'Isi ulasan perjalanan Anda.',
            'review.max' => 'Ulasan maksimal 500 karakter.',
            'review_photo.max' => 'Foto ulasan maksimal 5 MB.',
        ]);

        $userId = Auth::id();
        $destinationId = $request->destination_id;

        // Cek eligibility: harus punya booking yang completed
        $eligible = Booking::where('user_id', $userId)
            ->where('destination_id', $destinationId)
            ->where('trip_status', 'completed')
            ->exists();

        if (!$eligible) {
            return back()->with('review_error', 'Ulasan hanya bisa diberikan setelah perjalanan destinasi tersebut selesai.');
        }

        if (Review::where('user_id', $userId)->where('destination_id', $destinationId)->exists()) {
            return back()->with('review_error', 'Anda sudah pernah memberikan ulasan untuk destinasi ini.');
        }

        $reviewImage = null;
        if ($request->hasFile('review_photo')) {
            $file = $request->file('review_photo');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9_\.\-]/', '_', $file->getClientOriginalName());
            $destinationPath = public_path('uploads/reviews');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $filename);
            $reviewImage = $filename;
        }

        Review::create([
            'user_id' => $userId,
            'destination_id' => $destinationId,
            'rating' => $request->rating,
            'review' => $request->review,
            'review_image' => $reviewImage,
            'created_at' => now(),
        ]);

        // Update rata-rata rating destinasi
        $avgRating = Review::where('destination_id', $destinationId)->avg('rating');
        if ($avgRating !== null) {
            Destination::where('id', $destinationId)->update([
                'rating' => round($avgRating, 1),
            ]);
        }

        return redirect()->route('user.ulasan')->with('review_message', 'Ulasan berhasil dikirim. Terima kasih sudah berbagi pengalaman!');
    }

    /**
     * Profil Pengguna
     */
    public function profile()
    {
        $user = Auth::user();
        $favorites_count = Favorite::where('user_id', $user->id)->count();
        $bookings_count = Booking::where('user_id', $user->id)->count();
        $reviews_count = Review::where('user_id', $user->id)->count();

        return view('user.profile', compact('user', 'favorites_count', 'bookings_count', 'reviews_count'));
    }

    /**
     * Update Profil
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9_\.\-]/', '_', $file->getClientOriginalName());
            $destinationPath = public_path('uploads/profile');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $filename);
            $updateData['photo'] = $filename;
        }

        User::where('id', $user->id)->update($updateData);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Helper: Menyiapkan Detail Destinasi (Itinerary, Fasilitas, Ikon)
     */
    protected function prepareDestinationDetail($dest)
    {
        $category = $dest->category;
        $title = $dest->title;
        $days = 1;

        if (!empty($dest->trip_date)) {
            preg_match('/(\d+)\s+\w+\s*-\s*(\d+)/', $dest->trip_date, $m);
            if (!empty($m[1]) && !empty($m[2])) {
                $days = abs((int)$m[2] - (int)$m[1]) + 1;
                if ($days < 1) $days = 1;
            }
        }

        $itineraries = [];
        if (!empty($dest->itinerary)) {
            $decoded = json_decode($dest->itinerary, true);
            if (is_array($decoded)) {
                $itineraries = $decoded;
            }
        }

        $facilities = [];
        if (!empty($dest->facilities)) {
            $decoded = json_decode($dest->facilities, true);
            if (is_array($decoded)) {
                $facilities = $decoded;
            } else {
                $facilities = array_filter(array_map('trim', explode(',', $dest->facilities)));
            }
        } else {
            if ($category === 'Gunung') {
                $facilities = ['Transportasi', 'Tenda & Matras', 'Porter', 'Makan', 'P3K'];
            } elseif ($category === 'Pantai') {
                $facilities = ['Transportasi', 'Penginapan', 'Snorkeling Gear', 'Makan', 'Pemandu Lokal'];
            } elseif ($category === 'Air Terjun') {
                $facilities = ['Transportasi', 'Pemandu', 'Makan Siang', 'Tiket Masuk', 'Dokumentasi'];
            } elseif ($category === 'Kota') {
                $facilities = ['Transportasi', 'Penginapan', 'Tiket Wisata', 'Makan', 'Pemandu'];
            } else {
                $facilities = ['Transportasi', 'Pemandu', 'Makan', 'Dokumentasi'];
            }
        }

        if (empty($itineraries)) {
            if ($category === 'Gunung') {
                $itineraries = [
                    ['label' => 'Hari ke-1', 'title' => 'Perjalanan & Aklimatisasi', 'desc' => 'Tiba di basecamp, registrasi, briefing pendakian, istirahat dan persiapan perlengkapan.', 'time' => '10.00 - 18.00 WIB'],
                    ['label' => 'Hari ke-2', 'title' => 'Summit Attack & Sunrise', 'desc' => 'Dini hari mendaki ke puncak untuk menikmati sunrise terbaik dan panorama alam yang menakjubkan.', 'time' => '02.00 - 14.00 WIB'],
                ];
                if ($days >= 3) {
                    $itineraries[] = ['label' => 'Hari ke-3', 'title' => 'Turun & Kembali', 'desc' => 'Turun gunung, pembersihan peralatan, oleh-oleh, dan perjalanan pulang ke kota.', 'time' => '07.00 - 18.00 WIB'];
                }
            } elseif ($category === 'Pantai') {
                $itineraries = [
                    ['label' => 'Hari ke-1', 'title' => 'Tiba & Explore Pantai', 'desc' => 'Tiba di lokasi, check-in penginapan, eksplorasi pantai, snorkeling dan menikmati senja.', 'time' => '10.00 - 18.00 WIB'],
                    ['label' => 'Hari ke-2', 'title' => 'Island Hopping & Diving', 'desc' => 'Keliling pulau-pulau sekitar, diving, menikmati keindahan bawah laut dan kuliner lokal.', 'time' => '07.00 - 17.00 WIB'],
                ];
                if ($days >= 3) {
                    $itineraries[] = ['label' => 'Hari ke-3', 'title' => 'Sunrise & Kepulangan', 'desc' => 'Menikmati sunrise di pantai, sarapan khas lokal, belanja oleh-oleh, dan perjalanan pulang.', 'time' => '05.00 - 15.00 WIB'];
                }
            } elseif ($category === 'Air Terjun') {
                $itineraries = [
                    ['label' => 'Hari ke-1', 'title' => 'Tiba & Trekking Awal', 'desc' => 'Tiba di lokasi, trekking ringan menuju basecamp, menikmati suasana alam sekitar.', 'time' => '09.00 - 17.00 WIB'],
                    ['label' => 'Hari ke-2', 'title' => 'Kunjungi Air Terjun Utama', 'desc' => 'Trekking ke air terjun utama, mandi di kolam alami, foto-foto, dan menikmati udara segar pegunungan.', 'time' => '07.00 - 16.00 WIB'],
                ];
                if ($days >= 3) {
                    $itineraries[] = ['label' => 'Hari ke-3', 'title' => 'Air Terjun Tersembunyi & Pulang', 'desc' => 'Menjelajahi air terjun tersembunyi di sekitar lokasi, piknik, oleh-oleh, dan kembali ke kota.', 'time' => '08.00 - 18.00 WIB'];
                }
            } elseif ($category === 'Kota') {
                $itineraries = [
                    ['label' => 'Hari ke-1', 'title' => 'City Tour & Kuliner', 'desc' => 'Kunjungi landmark ikonik kota, wisata kuliner lokal, museum, dan pusat perbelanjaan.', 'time' => '09.00 - 20.00 WIB'],
                    ['label' => 'Hari ke-2', 'title' => 'Wisata Budaya & Belanja', 'desc' => 'Mengunjungi situs budaya dan bersejarah, belanja oleh-oleh khas, dan hiburan malam kota.', 'time' => '10.00 - 21.00 WIB'],
                ];
                if ($days >= 3) {
                    $itineraries[] = ['label' => 'Hari ke-3', 'title' => 'Wisata Alam Pinggir Kota & Pulang', 'desc' => 'Kunjungi destinasi alam di sekitar kota, sarapan khas, dan perjalanan pulang.', 'time' => '08.00 - 17.00 WIB'];
                }
            } else {
                $itineraries = [
                    ['label' => 'Hari ke-1', 'title' => 'Tiba & Eksplorasi', 'desc' => 'Tiba di lokasi, orientasi, eksplorasi area sekitar dan menikmati suasana setempat.', 'time' => '09.00 - 17.00 WIB'],
                ];
            }
        }

        $facility_icons = [
            'Transportasi' => 'bus',
            'Penginapan' => 'hotel',
            'Tiket Masuk' => 'ticket',
            'Tiket Wisata' => 'ticket',
            'Makan' => 'utensils',
            'Makan Siang' => 'utensils',
            'Dokumentasi' => 'camera',
            'Pemandu' => 'user-check',
            'Pemandu Lokal' => 'user-check',
            'Porter' => 'backpack',
            'P3K' => 'heart-pulse',
            'Snorkeling Gear' => 'waves',
            'Tenda & Matras' => 'tent',
            'Asuransi' => 'shield-check',
        ];

        return [
            'category' => $category,
            'title' => $title,
            'days' => $days,
            'itineraries' => $itineraries,
            'facilities' => $facilities,
            'facility_icons' => $facility_icons,
        ];
    }

    /**
     * Helper konversi format harga ke integer
     */
    protected function priceToInt($price)
    {
        $raw = strtolower(trim((string)$price));
        $raw = str_replace(['rp', 'idr', ' ', '.'], '', $raw);

        if (strpos($raw, 'jt') !== false || strpos($raw, 'juta') !== false) {
            $number = str_replace(['juta', 'jt'], '', $raw);
            $number = str_replace(',', '.', $number);
            return (int)round((float)$number * 1000000);
        }

        return (int)preg_replace('/[^0-9]/', '', $raw);
    }
}
