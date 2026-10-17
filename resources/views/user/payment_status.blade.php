@extends('layouts.app')

@section('title', 'Status Pembayaran - Wandee')
@section('body-class', 'payment-detail-page')

@section('content')
@include('partials.navbar')

<main class="user-page-container">
    <section class="section-header">
        <h1>Status Pembayaran</h1>
        <p>Periksa status pembayaran dan perjalanan Anda.</p>
    </section>

    <section class="payment-status-card">
        <h2>{{ $destination->title }}</h2>
        <p><strong>Status perjalanan:</strong> {{ ucfirst($booking->trip_status) }}</p>
        <p><strong>Status pembayaran:</strong> {{ ucfirst($payment->payment_status) }}</p>

        <div class="status-details">
            <p><strong>Total bayar:</strong> Rp {{ number_format((int)$payment_total, 0, ',', '.') }}</p>
            <p><strong>Kode voucher:</strong> {{ $voucher_code !== '' && $voucher_code !== '0' ? $voucher_code : '-' }}</p>
            <p><strong>Metode pembayaran:</strong> {{ strtoupper($payment->payment_method) }}</p>
        </div>

        @if($payment->payment_proof)
            <div class="proof-preview">
                <h3>Bukti Pembayaran</h3>
                <img src="{{ asset('uploads/payments/' . $payment->payment_proof) }}" alt="Bukti Pembayaran">
            </div>
        @endif

        @if($payment->payment_status === 'waiting')
            <div class="alert alert-warning">
                Bukti pembayaran sedang menunggu verifikasi admin.
            </div>
        @elseif($payment->payment_status === 'verified')
            <div class="alert alert-success">
                Pembayaran berhasil diverifikasi. Silakan ikuti jadwal perjalanan.
            </div>
        @elseif($payment->payment_status === 'rejected')
            <div class="alert alert-danger">
                <strong>Pembayaran ditolak.</strong><br>
                @if(!empty($payment->rejection_reason))
                    <strong>Alasan:</strong> {{ $payment->rejection_reason }}
                @else
                    Mohon unggah ulang bukti pembayaran yang valid.
                @endif
            </div>
        @endif

        <div style="margin-top: 24px; display: flex; gap: 12px; flex-wrap: wrap;">
            @if($payment->payment_status !== 'verified')
                <a href="{{ route('user.payment_detail', ['booking_id' => $booking->id, 'payment_id' => $payment->id]) }}" class="btn-secondary">
                    Unggah Ulang Bukti
                </a>
            @endif
            <a href="{{ route('user.riwayat') }}" class="btn-primary">
                Kembali ke Riwayat
            </a>
        </div>
    </section>
</main>

@include('partials.footer')
@endsection
