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
                @endphp
                <article class="trip-history-card">
                    <img src="{{ asset('assets/img/' . ($booking->destination->image ?? 'gunung.png')) }}" 
                         alt="{{ $booking->destination->title ?? 'Trip' }}"
                         onerror="this.src='{{ asset('assets/img/gunung.png') }}'">
                    
                    <div class="trip-history-info">
                        <h3>{{ $booking->destination->title ?? 'Destinasi Tidak Ditemukan' }}</h3>
                        <p class="trip-location">
                            <i data-lucide="map-pin"></i>
                            {{ $booking->destination->location ?? '-' }}
                        </p>
                        <div class="trip-history-meta">
                            <span><i data-lucide="users"></i>{{ (int)$booking->total_people }} orang</span>
                            <span><i data-lucide="route"></i>{{ $tripLabel }}</span>
                            <span><i data-lucide="credit-card"></i>{{ $paymentLabel }}</span>
                        </div>
                    </div>

                    <div class="trip-history-action">
                        <span class="trip-status">{{ $paymentLabel }}</span>
                        <div class="trip-action-buttons">
                            @if($paymentStatus === 'waiting' || $paymentStatus === 'pending')
                                <a href="{{ route('user.payment_detail', ['booking_id' => $booking->id, 'payment_id' => $payment->id ?? null]) }}" class="btn-primary">
                                    Lengkapi Pembayaran
                                </a>
                            @else
                                <a href="{{ route('user.payment_status', ['booking_id' => $booking->id]) }}" class="btn-secondary">
                                    Lihat Status
                                </a>
                            @endif

                            @if($booking->trip_status === 'completed')
                                <a href="{{ route('user.ulasan') }}" class="btn-primary" style="margin-left: 8px;">
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
