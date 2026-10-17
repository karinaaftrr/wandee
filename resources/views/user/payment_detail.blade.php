@extends('layouts.app')

@section('title', 'Detail Pembayaran - Wandee')
@section('body-class', 'payment-detail-page')

@section('content')
@include('partials.navbar')

<main class="user-page-container">
    <section class="section-header">
        <h1>Detail Pembayaran</h1>
        <p>Ikuti instruksi untuk menyelesaikan pembayaran Anda.</p>
    </section>

    <section class="payment-detail-card">
        <div class="payment-info">
            <h2>{{ $destination->title }}</h2>
            <p>{{ $destination->location }}</p>
            <div class="payment-detail-rows">
                <p><span>Jumlah orang</span><strong>{{ (int)$booking->total_people }} Orang</strong></p>
                <p><span>Harga per orang</span><strong>Rp {{ number_format((int)$price, 0, ',', '.') }}</strong></p>
                <p><span>Total harga</span><strong>Rp {{ number_format((int)$payment->payment_amount, 0, ',', '.') }}</strong></p>
                <p><span>Kode voucher</span><strong>{{ $voucher_code !== '' && $voucher_code !== '0' ? $voucher_code : '-' }}</strong></p>
                <p class="payment-detail-total"><span>Total harus dibayar</span><strong>Rp {{ number_format((int)$payment_total, 0, ',', '.') }}</strong></p>
                <p><span>Status pembayaran</span><strong>{{ ucfirst($payment->payment_status) }}</strong></p>
            </div>
        </div>

        <div class="payment-instructions">
            <h3>Informasi Transfer</h3>
            @php
                $bankAccounts = [
                    'bca' => 'BCA - 1234567890 (a/n Wandee Travel)',
                    'bri' => 'BRI - 0987654321 (a/n Wandee Travel)',
                    'bni' => 'BNI - 1122334455 (a/n Wandee Travel)',
                    'qris' => 'Scan QRIS yang tersedia di aplikasi dompet digital Anda'
                ];
                $method = strtolower($payment->payment_method);
            @endphp
            <p>{{ $bankAccounts[$method] ?? 'Silakan transfer sesuai total tagihan ke rekening Wandee Travel.' }}</p>

            @if ($payment->payment_status !== 'verified')
                @if (!empty($payment->payment_proof))
                    <div class="proof-preview">
                        <h3>Bukti Pembayaran Tersimpan</h3>
                        <img src="{{ asset('uploads/payments/' . $payment->payment_proof) }}" alt="Bukti Pembayaran">
                        <div class="alert alert-warning">
                            Bukti pembayaran sudah terkirim dan sedang menunggu verifikasi admin.
                        </div>
                    </div>
                @endif

                <h3>{{ !empty($payment->payment_proof) ? 'Ganti Bukti Pembayaran' : 'Unggah Bukti Pembayaran' }}</h3>
                <p>{{ !empty($payment->payment_proof) ? 'Upload ulang hanya jika bukti sebelumnya salah. File terbaru akan menggantikan bukti yang dilihat admin.' : 'Upload bukti transfer untuk mempercepat validasi. Maksimal 5 MB, format JPG/JPEG/PNG.' }}</p>

                <form action="{{ route('user.payment_upload') }}" method="POST" enctype="multipart/form-data" class="payment-proof-form">
                    @csrf
                    <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                    <input type="hidden" name="payment_id" value="{{ $payment->id }}">
                    <div class="form-group">
                        <label for="payment_proof">Bukti Pembayaran</label>
                        <input type="file" id="payment_proof" name="payment_proof" accept="image/jpeg,image/png,image/jpg" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn-primary">{{ !empty($payment->payment_proof) ? 'Ganti Bukti' : 'Upload Bukti' }}</button>
                    </div>
                </form>
            @else
                <div class="alert alert-success">
                    Pembayaran sudah diverifikasi. Terima kasih! Silakan cek status perjalanan Anda di halaman riwayat.
                </div>
            @endif

            <a href="{{ route('user.payment_status', ['booking_id' => $booking->id]) }}" class="btn-secondary">Lihat Status Pembayaran</a>
        </div>
    </section>
</main>

@include('partials.footer')
@endsection
