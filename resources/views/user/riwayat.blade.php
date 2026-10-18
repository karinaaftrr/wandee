
@extends('layouts.app')

@section('title', 'Riwayat Perjalanan - Wandee')
@section('body-class', 'trip-page')

@section('content')
@include('partials.navbar')

<main class="trip-wrap">
    <section class="trip-hero">
        <h1>Riwayat Perjalanan</h1>
        <p>Semua pemesanan dan status perjalanan Anda tersimpan di sini.</p>
    </section>

    @if($bookings->isEmpty())
        <div class="empty-state-card" style="text-align: center; padding: 48px; background: var(--bg-card); border-radius: var(--radius-md); border: 1px solid var(--border);">
            <p style="margin-bottom: 20px; font-size: 1.1rem; color: var(--text-muted);">Anda belum mengikuti perjalanan apapun.</p>
            <a href="{{ url('/#destinations') }}" class="btn-primary">Cari Trip Baru</a>
        </div>
    @else
        <section class="trip-history-list">
            @foreach($bookings as $booking)
                @php
                    $payment = $booking->payment;
                    $paymentStatus = $payment->payment_status ?? $booking->payment_status;
                    $paymentLabel = ucfirst($paymentStatus);
                    $tripLabel = ucfirst($booking->trip_status ?? 'new');

                    $isAccepted = in_array($booking->trip_status, ['accepted', 'completed', 'ongoing']);
                    $isVerified = $paymentStatus === 'verified';
                    $isRejected = $paymentStatus === 'rejected';

                    // Ulasan diperiksa berdasarkan ID pemesanan.
                    $alreadyReviewed = in_array(
                        (int) $booking->id,
                        $reviewedBookingIds ?? [],
                        true
                    );
                @endphp

                <article class="trip-history-card {{ $isAccepted ? 'trip-card-clickable' : '' }}"
                    @if($isAccepted)
                        onclick="window.location='{{ route('user.trip_detail', ['booking_id' => $booking->id]) }}'"
                        style="cursor: pointer;"
                    @endif>

                    <img
                        src="{{ asset('assets/img/' . ($booking->destination->image ?? 'gunung.png')) }}"
                        alt="{{ $booking->destination->title ?? 'Trip' }}"
                        onerror="this.src='{{ asset('assets/img/gunung.png') }}'">

                    <div class="trip-history-info">
                        <h3>{{ $booking->destination->title ?? 'Destinasi Tidak Ditemukan' }}</h3>

                        <p class="trip-location">
                            <i data-lucide="map-pin"></i>
                            {{ $booking->destination->location ?? '-' }}
                        </p>

                        <div class="trip-history-meta">
                            <span>
                                <i data-lucide="users"></i>
                                {{ (int) $booking->total_people }} orang
                            </span>

                            <span>
                                <i data-lucide="route"></i>
                                {{ $tripLabel }}
                            </span>

                            <span @if($isRejected) style="background: rgba(248,113,113,0.12); color: #f87171;" @endif>
                                <i data-lucide="credit-card" @if($isRejected) style="color: #f87171;" @endif></i>
                                {{ $paymentLabel }}
                            </span>
                        </div>
                    </div>

                    <div class="trip-history-action">
                        <span class="trip-status" @if($isRejected) style="background: rgba(248,113,113,0.12); color: #f87171;" @endif>
                            {{ $paymentLabel }}
                        </span>

                        <div class="trip-action-buttons">
                            @if($paymentStatus === 'waiting' || $paymentStatus === 'pending')
                                @if(!empty($payment->payment_proof))
                                    <a
                                        href="{{ route('user.payment_status', ['booking_id' => $booking->id]) }}"
                                        class="btn-secondary"
                                        onclick="event.stopPropagation()">
                                        Lihat Bukti Pembayaran
                                    </a>
                                @else
                                    <a
                                        href="{{ route('user.payment_detail', ['booking_id' => $booking->id, 'payment_id' => $payment->id ?? null]) }}"
                                        class="btn-primary"
                                        onclick="event.stopPropagation()">
                                        Lengkapi Pembayaran
                                    </a>
                                @endif
                            @else
                                <a
                                    href="{{ route('user.trip_detail', ['booking_id' => $booking->id]) }}"
                                    class="btn-secondary"
                                    onclick="event.stopPropagation()">
                                    Lihat Status
                                </a>
                            @endif

                            @if($booking->trip_status === 'completed' && $isVerified && !$alreadyReviewed)
                                <a
                                    href="{{ route('user.ulasan', ['booking_id' => $booking->id]) }}"
                                    class="btn-primary"
                                    style="margin-left: 8px;"
                                    onclick="event.stopPropagation()">
                                    Beri Ulasan
                                </a>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </section>
    @endif
</main>

@include('partials.footer')
@endsection
