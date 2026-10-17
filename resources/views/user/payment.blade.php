@extends('layouts.app')

@section('title', 'Pembayaran - Wandee')
@section('body-class', 'payment-page')

@section('content')
@include('partials.navbar')

<main class="payment-shell">
    <section class="section-header">
        <h1>Bayar Perjalanan</h1>
        <p>Periksa detail pembayaran dan pilih metode yang nyaman untuk Anda.</p>
    </section>

    <section class="payment-deadline">
        <div class="payment-deadline-icon">
            <i data-lucide="clock-3"></i>
        </div>
        <p>
            <strong>Selesaikan pembayaran Anda</strong><br>
            Pilih metode pembayaran, lalu lanjutkan untuk melihat rekening dan total transfer.
        </p>
    </section>

    <section class="payment-trip-card">
        <img src="{{ asset('assets/img/' . $destination->image) }}" alt="{{ $destination->title }}" onerror="this.src='{{ asset('assets/img/gunung.png') }}'">
        <div class="payment-trip-info">
            <h1>{{ $destination->title }}</h1>
            <p>{{ $destination->location }}</p>
            <span>Open Trip</span>
        </div>
        <div class="payment-trip-price">
            <div>
                <span>Jumlah orang</span>
                <strong>{{ (int)$quantity }} Orang</strong>
            </div>
            <div>
                <span>Harga per orang</span>
                <strong>Rp {{ number_format((int)$price, 0, ',', '.') }}</strong>
            </div>
            <div class="payment-total-line">
                <span>Total harga</span>
                <strong>Rp {{ number_format((int)$total_price, 0, ',', '.') }}</strong>
            </div>
        </div>
    </section>

    <form action="{{ route('user.payment_init') }}" method="POST" class="payment-form">
        @csrf
        <input type="hidden" name="destination_id" value="{{ $destination->id }}">
        <input type="hidden" name="total_people" value="{{ (int)$quantity }}">

        <section class="payment-grid">
            <div class="payment-main">
                <div class="payment-panel">
                    <h2>Metode Pembayaran</h2>
                    <div class="payment-methods">
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="bca" required>
                            <span class="payment-radio"></span>
                            <strong>BCA</strong>
                            <span>Bank BCA</span>
                        </label>
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="bri" required>
                            <span class="payment-radio"></span>
                            <strong>BRI</strong>
                            <span>Bank BRI</span>
                        </label>
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="bni" required>
                            <span class="payment-radio"></span>
                            <strong>BNI</strong>
                            <span>Bank BNI</span>
                        </label>
                        <label class="payment-method payment-method-wide">
                            <input type="radio" name="payment_method" value="qris" required>
                            <span class="payment-radio"></span>
                            <strong>QRIS</strong>
                            <span>Scan QR code di aplikasi dompet digital Anda</span>
                        </label>
                    </div>
                </div>
            </div>

            <aside class="payment-summary">
                <h2>Informasi Pesanan</h2>
                <div class="payment-row">
                    <span>Harga per orang</span>
                    <strong>Rp {{ number_format((int)$price, 0, ',', '.') }}</strong>
                </div>
                <div class="payment-row">
                    <span>Jumlah peserta</span>
                    <strong>{{ (int)$quantity }} Orang</strong>
                </div>
                <div class="payment-row payment-row-total">
                    <span>Total Pembayaran</span>
                    <strong>Rp {{ number_format((int)$total_price, 0, ',', '.') }}</strong>
                </div>
                <div class="payment-note">
                    <h3>Catatan</h3>
                    <p><i data-lucide="info"></i> Pembayaran akan diverifikasi oleh admin setelah bukti transfer diunggah.</p>
                </div>
                <button type="submit" class="btn-primary payment-pay-button">Lanjutkan ke Instruksi Pembayaran</button>
            </aside>
        </section>
    </form>
</main>

@include('partials.footer')
@endsection
