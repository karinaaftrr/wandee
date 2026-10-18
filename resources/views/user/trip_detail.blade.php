@extends('layouts.app')

@section('title', ($destination->title ?? 'Detail Trip') . ' - Wandee')
@section('body-class', 'payment-detail-page')

@section('content')
@include('partials.navbar')

<main class="user-page-container">

    {{-- Back Button --}}
    <a href="{{ route('user.riwayat') }}" class="btn-secondary" style="display:inline-flex; align-items:center; gap:6px; margin-bottom:24px; font-size:14px;">
        <i data-lucide="arrow-left"></i> Kembali ke Riwayat
    </a>

    {{-- Hero / Destination Header --}}
    <section class="trip-detail-hero" style="
        position: relative;
        border-radius: var(--radius-lg);
        overflow: hidden;
        margin-bottom: 28px;
        border: 1px solid var(--border);
    ">
        <img
            src="{{ asset('assets/img/' . ($destination->image ?? 'gunung.png')) }}"
            alt="{{ $destination->title }}"
            onerror="this.src='{{ asset('assets/img/gunung.png') }}'"
            style="width:100%; height:260px; object-fit:cover; display:block; filter: brightness(0.55);">
        <div style="
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 28px 32px;
            background: linear-gradient(to top, rgba(10,15,13,0.92) 0%, transparent 60%);
        ">
            <span style="
                display: inline-block;
                background: rgba(57,255,120,0.15);
                color: var(--accent);
                border: 1px solid rgba(57,255,120,0.3);
                border-radius: 20px;
                font-size: 12px;
                font-weight: 600;
                padding: 4px 14px;
                margin-bottom: 10px;
                width: fit-content;
                letter-spacing: .04em;
                text-transform: uppercase;
            ">{{ $destination->category ?? 'Trip' }}</span>
            <h1 style="color: var(--white); font-size: 1.75rem; font-weight: 800; margin-bottom: 8px;">
                {{ $destination->title ?? 'Destinasi' }}
            </h1>
            <div style="display:flex; align-items:center; gap: 18px; flex-wrap:wrap;">
                <span style="color: var(--text-muted); font-size:14px; display:flex; align-items:center; gap:5px;">
                    <i data-lucide="map-pin" style="width:14px;height:14px;"></i>
                    {{ $destination->location ?? '-' }}
                </span>
                <span style="color: var(--text-muted); font-size:14px; display:flex; align-items:center; gap:5px;">
                    <i data-lucide="calendar-days" style="width:14px;height:14px;"></i>
                    {{ $destination->trip_date ?: '-' }}
                </span>
                <span style="color: var(--text-muted); font-size:14px; display:flex; align-items:center; gap:5px;">
                    <i data-lucide="star" style="width:14px;height:14px; color: #fbbf24;"></i>
                    {{ number_format((float)($destination->rating ?? 5.0), 1) }}
                </span>
            </div>
        </div>
    </section>

    {{-- Two-column grid --}}
    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:24px;">

        {{-- Booking Detail Card --}}
        <div style="
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 24px 26px;
        ">
            <h2 style="font-size:1rem; font-weight:700; color:var(--text); margin-bottom:18px; display:flex; align-items:center; gap:8px;">
                <i data-lucide="clipboard-list" style="width:16px;height:16px; color:var(--accent);"></i>
                Detail Pemesanan
            </h2>
            <div style="display:flex; flex-direction:column; gap:12px;">
                <div style="display:flex; justify-content:space-between; align-items:center; font-size:14px;">
                    <span style="color:var(--text-muted);">Jumlah Peserta</span>
                    <span style="color:var(--text); font-weight:600;">{{ (int)$booking->total_people }} orang</span>
                </div>
                <div style="display:flex; justify-content:space-between; align-items:center; font-size:14px;">
                    <span style="color:var(--text-muted);">Total Harga</span>
                    <span style="color:var(--accent); font-weight:700;">Rp {{ number_format((int)$booking->total_price, 0, ',', '.') }}</span>
                </div>
                <div style="display:flex; justify-content:space-between; align-items:center; font-size:14px;">
                    <span style="color:var(--text-muted);">Metode Bayar</span>
                    <span style="color:var(--text); font-weight:600;">{{ strtoupper($payment->payment_method ?? '-') }}</span>
                </div>
                <hr style="border-color:var(--border); margin: 4px 0;">
                <div style="display:flex; justify-content:space-between; align-items:center; font-size:14px;">
                    <span style="color:var(--text-muted);">Status Pembayaran</span>
                    @php
                        $pStatus = $payment->payment_status ?? 'pending';
                        $pColor = match($pStatus) {
                            'verified' => '#4ade80',
                            'waiting'  => '#fbbf24',
                            'rejected' => '#f87171',
                            default    => '#94a3b8',
                        };
                    @endphp
                    <span style="color:{{ $pColor }}; font-weight:700; font-size:13px; text-transform:capitalize;">{{ ucfirst($pStatus) }}</span>
                </div>
                <div style="display:flex; justify-content:space-between; align-items:center; font-size:14px;">
                    <span style="color:var(--text-muted);">Status Trip</span>
                    @php
                        $tStatus = $booking->trip_status ?? 'new';
                        $tColor = match($tStatus) {
                            'completed' => '#4ade80',
                            'ongoing'   => '#60a5fa',
                            'accepted'  => '#a78bfa',
                            default     => '#94a3b8',
                        };
                    @endphp
                    <span style="color:{{ $tColor }}; font-weight:700; font-size:13px; text-transform:capitalize;">{{ ucfirst($tStatus) }}</span>
                </div>
            </div>
        </div>

        {{-- Payment Status Alert Card --}}
        <div style="
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 24px 26px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        ">
            <div>
                <h2 style="font-size:1rem; font-weight:700; color:var(--text); margin-bottom:18px; display:flex; align-items:center; gap:8px;">
                    <i data-lucide="info" style="width:16px;height:16px; color:var(--accent);"></i>
                    Status Perjalanan
                </h2>
                @if($payment->payment_status === 'verified' && $booking->trip_status === 'completed')
                    <div style="background:rgba(74,222,128,0.08); border:1px solid rgba(74,222,128,0.25); border-radius:var(--radius-sm); padding:14px 16px; color:#4ade80; font-size:14px; line-height:1.6; margin-bottom:16px;">
                        <strong>✓ Perjalanan Selesai</strong><br>
                        Pembayaran terverifikasi dan trip telah berhasil diselesaikan. Terima kasih telah bersama Wandee!
                    </div>
                @elseif($payment->payment_status === 'verified' && $booking->trip_status === 'ongoing')
                    <div style="background:rgba(96,165,250,0.08); border:1px solid rgba(96,165,250,0.25); border-radius:var(--radius-sm); padding:14px 16px; color:#60a5fa; font-size:14px; line-height:1.6; margin-bottom:16px;">
                        <strong>✈ Trip Sedang Berlangsung</strong><br>
                        Pembayaran terverifikasi. Perjalanan Anda sedang berjalan. Nikmati setiap momennya!
                    </div>
                @elseif($payment->payment_status === 'verified')
                    <div style="background:rgba(167,139,250,0.08); border:1px solid rgba(167,139,250,0.25); border-radius:var(--radius-sm); padding:14px 16px; color:#a78bfa; font-size:14px; line-height:1.6; margin-bottom:16px;">
                        <strong>✓ Booking Diterima</strong><br>
                        Pembayaran terverifikasi. Bersiaplah untuk perjalanan Anda!
                    </div>
                @elseif($payment->payment_status === 'waiting')
                    <div style="background:rgba(251,191,36,0.08); border:1px solid rgba(251,191,36,0.25); border-radius:var(--radius-sm); padding:14px 16px; color:#fbbf24; font-size:14px; line-height:1.6; margin-bottom:16px;">
                        <strong>⏳ Menunggu Verifikasi</strong><br>
                        Bukti pembayaran Anda sedang ditinjau oleh admin. Mohon tunggu konfirmasi.
                    </div>
                @elseif($payment->payment_status === 'rejected')
                    <div style="background:rgba(248,113,113,0.08); border:1px solid rgba(248,113,113,0.25); border-radius:var(--radius-sm); padding:14px 16px; color:#f87171; font-size:14px; line-height:1.6; margin-bottom:16px;">
                        <strong>✕ Pembayaran Ditolak</strong><br>
                        @if(!empty($payment->rejection_reason))
                            Alasan: {{ $payment->rejection_reason }}
                        @else
                            Mohon unggah ulang bukti pembayaran yang valid.
                        @endif
                    </div>
                @else
                    <div style="background:rgba(148,163,184,0.08); border:1px solid rgba(148,163,184,0.2); border-radius:var(--radius-sm); padding:14px 16px; color:#94a3b8; font-size:14px; line-height:1.6; margin-bottom:16px;">
                        <strong>Menunggu pembayaran.</strong><br>
                        Silakan lengkapi pembayaran Anda untuk mengkonfirmasi booking.
                    </div>
                @endif
            </div>

            {{-- Action Buttons --}}
            <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:8px;">
                @if($payment->payment_status !== 'verified')
                    <a href="{{ route('user.payment_status', ['booking_id' => $booking->id]) }}"
                       class="btn-secondary"
                       style="font-size:13px; padding: 8px 16px;">
                        <i data-lucide="receipt" style="width:14px;height:14px;"></i>
                        Lihat Bukti Pembayaran
                    </a>
                @endif
                @if($booking->trip_status === 'completed' && $isVerified && !$hasReviewed)
                    <a href="{{ route('user.ulasan', ['destination_id' => $destination->id]) }}"
                       class="btn-primary"
                       style="font-size:13px; padding: 8px 16px;">
                        <i data-lucide="star" style="width:14px;height:14px;"></i>
                        Beri Ulasan
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Itinerary Section --}}
    @if(!empty($itineraries))
    <section style="
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 28px 30px;
        margin-bottom: 20px;
    ">
        <h2 style="font-size:1rem; font-weight:700; color:var(--text); margin-bottom:22px; display:flex; align-items:center; gap:8px;">
            <i data-lucide="map" style="width:16px;height:16px; color:var(--accent);"></i>
            Itinerary Perjalanan
        </h2>
        <div style="display:flex; flex-direction:column; gap:16px;">
            @foreach($itineraries as $i => $item)
            <div style="display:flex; gap:16px; position:relative;">
                {{-- Timeline dot --}}
                <div style="display:flex; flex-direction:column; align-items:center; flex-shrink:0;">
                    <div style="
                        width: 36px; height: 36px;
                        border-radius: 50%;
                        background: rgba(57,255,120,0.12);
                        border: 2px solid rgba(57,255,120,0.35);
                        display: flex; align-items:center; justify-content:center;
                        font-size: 12px; font-weight:700; color: var(--accent);
                    ">{{ $i + 1 }}</div>
                    @if(!$loop->last)
                    <div style="width:2px; flex:1; background: rgba(57,255,120,0.15); margin-top:8px; margin-bottom:-8px;"></div>
                    @endif
                </div>
                {{-- Content --}}
                <div style="padding-bottom: {{ $loop->last ? '0' : '16px' }}; flex:1;">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:6px; flex-wrap:wrap; gap:6px;">
                        <div>
                            <span style="font-size:11px; font-weight:600; color:var(--accent); text-transform:uppercase; letter-spacing:.04em;">
                                {{ $item['label'] ?? ('Hari ke-' . ($i+1)) }}
                            </span>
                            <h3 style="font-size:15px; font-weight:700; color:var(--text); margin-top:2px;">
                                {{ $item['title'] ?? '' }}
                            </h3>
                        </div>
                        @if(!empty($item['time']))
                        <span style="font-size:12px; color:var(--text-muted); background:rgba(255,255,255,0.05); border:1px solid var(--border); border-radius:20px; padding:3px 10px; white-space:nowrap;">
                            {{ $item['time'] }}
                        </span>
                        @endif
                    </div>
                    <p style="font-size:13px; color:var(--text-muted); line-height:1.65;">{{ $item['desc'] ?? '' }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Facilities Section --}}
    @if(!empty($facilities))
    <section style="
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 28px 30px;
        margin-bottom: 28px;
    ">
        <h2 style="font-size:1rem; font-weight:700; color:var(--text); margin-bottom:20px; display:flex; align-items:center; gap:8px;">
            <i data-lucide="package-check" style="width:16px;height:16px; color:var(--accent);"></i>
            Fasilitas Termasuk
        </h2>
        <div style="display:flex; flex-wrap:wrap; gap:10px;">
            @foreach($facilities as $facility)
            @php $icon = $facility_icons[$facility] ?? 'check-circle'; @endphp
            <div style="
                display: flex; align-items:center; gap:8px;
                background: rgba(57,255,120,0.06);
                border: 1px solid rgba(57,255,120,0.18);
                border-radius: 30px;
                padding: 7px 14px;
                font-size: 13px;
                color: var(--text);
                font-weight: 500;
            ">
                <i data-lucide="{{ $icon }}" style="width:14px;height:14px; color:var(--accent);"></i>
                {{ $facility }}
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Bottom back button --}}
    <div style="display:flex; justify-content:center; margin-bottom:16px;">
        <a href="{{ route('user.riwayat') }}" class="btn-primary" style="display:inline-flex; align-items:center; gap:8px; padding:12px 28px;">
            <i data-lucide="arrow-left" style="width:16px;height:16px;"></i>
            Kembali ke Riwayat
        </a>
    </div>

</main>

@include('partials.footer')
@endsection