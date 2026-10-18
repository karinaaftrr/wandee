@extends('layouts.admin')

@section('title', 'Verifikasi Pembayaran - Wandee')

@push('styles')
<style>
    .proof-thumb {
        width: 56px;
        height: 56px;
        padding: 0;
        overflow: hidden;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, .14);
        background: #0b1510;
        cursor: zoom-in;
        transition: .2s ease;
    }

    .proof-thumb:hover {
        border-color: rgba(57, 255, 120, .5);
        transform: translateY(-1px);
    }

    .proof-thumb img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .proof-empty {
        color: rgba(232, 240, 235, .45);
        font-size: .8rem;
        white-space: nowrap;
    }

    .proof-modal {
        position: fixed;
        inset: 0;
        z-index: 10000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 24px;
    }

    .proof-modal.is-open {
        display: flex;
    }

    .proof-modal-backdrop {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, .78);
        backdrop-filter: blur(6px);
    }

    .proof-modal-dialog {
        position: relative;
        width: min(760px, 100%);
        max-height: calc(100vh - 48px);
        overflow: auto;
        padding: 26px;
        border-radius: 24px;
        border: 1px solid rgba(255, 255, 255, .1);
        background: #0b1510;
    }

    .proof-modal-dialog h3 {
        margin: 0 44px 4px 0;
        font-size: 1.1rem;
    }

    .proof-modal-dialog p {
        margin: 0 0 16px;
        color: rgba(232, 240, 235, .62);
        font-size: .9rem;
    }

    .proof-modal-imgwrap {
        overflow: hidden;
        border-radius: 16px;
        background: #050a07;
        text-align: center;
    }

    .proof-modal-imgwrap img {
        max-width: 100%;
        max-height: 62vh;
        object-fit: contain;
    }

    .proof-modal-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 16px;
    }

    .proof-modal-x {
        position: absolute;
        top: 14px;
        right: 16px;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: 1px solid rgba(255, 255, 255, .14);
        background: transparent;
        color: #fff;
        font-size: 1.3rem;
        line-height: 1;
        cursor: pointer;
    }

    body.proof-open {
        overflow: hidden;
    }
</style>
@endpush

@section('content')

    <div class="admin-header-small">
        <div>
            <span class="admin-badge">Verifikasi Pembayaran</span>
            <h1>Manage Pembayaran</h1>
            <p>Periksa bukti pembayaran dari pengguna, lalu verifikasi atau tolak.</p>
        </div>
    </div>

    @if (session('payment_error'))
        <div style="background:rgba(255,79,129,.10);border:1px solid rgba(255,79,129,.35);color:#ff4f81;padding:14px 18px;border-radius:14px;margin-bottom:20px;font-size:.92rem;">
            {{ session('payment_error') }}
        </div>
    @endif

    {{-- FILTER --}}
    <div style="display:flex;gap:10px;margin-bottom:24px;flex-wrap:wrap;">
        @php $filters = ['all' => 'Semua', 'waiting' => 'Menunggu', 'verified' => 'Terverifikasi', 'rejected' => 'Ditolak']; @endphp
        @foreach ($filters as $key => $label)
            <a href="{{ route('admin.manage_payments', ['filter' => $key]) }}"
               class="{{ $filter === $key ? 'btn-primary' : 'btn-ghost' }}"
               style="padding:8px 18px;font-size:.82rem;">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="dashboard-table-card dashboard-table-card-custom">
        <div class="dashboard-table-wrap">
            <table class="dashboard-table-custom">
                <thead>
                    <tr class="dashboard-table-tr-header">
                        <th class="dashboard-table-th">ID</th>
                        <th class="dashboard-table-th">Pemesan</th>
                        <th class="dashboard-table-th">Destinasi</th>
                        <th class="dashboard-table-th">Peserta</th>
                        <th class="dashboard-table-th">Jumlah</th>
                        <th class="dashboard-table-th">Metode</th>
                        <th class="dashboard-table-th">Bukti</th>
                        <th class="dashboard-table-th">Status</th>
                        <th class="dashboard-table-th">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payments as $payment)
                        @php
                            $ps = $payment->payment_status ?? 'waiting';
                            $badgeClass = in_array($ps, ['verified', 'rejected'], true) ? $ps : 'waiting';
                            $hasProof = !empty($payment->payment_proof);
                            $proofUrl = $hasProof ? asset('uploads/payments/' . rawurlencode($payment->payment_proof)) : null;
                            $amountText = 'Rp ' . number_format($payment->payment_amount ?? 0, 0, ',', '.');
                        @endphp
                        <tr class="dashboard-table-tr">
                            <td class="dashboard-table-td">#{{ $payment->id }}</td>
                            <td class="dashboard-table-td"><strong>{{ $payment->user_name ?? 'Guest' }}</strong></td>
                            <td class="dashboard-table-td-muted">{{ $payment->destination_title ?? '-' }}</td>
                            <td class="dashboard-table-td-muted">{{ $payment->total_people ?? 0 }} Orang</td>
                            <td class="dashboard-table-td-accent" style="white-space:nowrap;">{{ $amountText }}</td>
                            <td class="dashboard-table-td-muted">{{ $payment->payment_method ?? '-' }}</td>
                            <td class="dashboard-table-td">
                                @if ($hasProof)
                                    <button type="button"
                                            class="proof-thumb"
                                            data-proof-open
                                            data-src="{{ $proofUrl }}"
                                            data-title="Bukti pembayaran #{{ $payment->id }} - {{ $payment->user_name ?? 'Guest' }}"
                                            data-meta="{{ ($payment->destination_title ?? '-') . ' | ' . $amountText . ' | ' . ($payment->payment_method ?? '-') }}"
                                            aria-label="Lihat bukti pembayaran #{{ $payment->id }}">
                                        <img src="{{ $proofUrl }}"
                                             alt="Bukti pembayaran #{{ $payment->id }}"
                                             loading="lazy"
                                             onerror="this.parentNode.textContent='File hilang'; ">
                                    </button>
                                @else
                                    <span class="proof-empty">Belum ada</span>
                                @endif
                            </td>
                            <td class="dashboard-table-td">
                                <span class="status-badge {{ $badgeClass }}">
                                    {{ $ps === 'verified' ? 'Verified' : ($ps === 'rejected' ? 'Rejected' : 'Waiting') }}
                                </span>
                            </td>
                            <td class="dashboard-table-td">
                                @if ($ps === 'waiting')
                                    <div style="display:flex;gap:6px;flex-wrap:wrap;">
                                        @if ($hasProof)
                                            <form action="{{ route('admin.payment_update') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="payment_id" value="{{ $payment->id }}">
                                                <input type="hidden" name="status" value="verified">
                                                <button type="submit" class="btn-primary" style="padding:6px 14px;font-size:.75rem;">Verifikasi</button>
                                            </form>
                                        @else
                                            <button type="button" class="btn-primary" disabled
                                                    title="Belum ada bukti pembayaran"
                                                    style="padding:6px 14px;font-size:.75rem;opacity:.4;cursor:not-allowed;">Verifikasi</button>
                                        @endif
                                        <form action="{{ route('admin.payment_update') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="payment_id" value="{{ $payment->id }}">
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="btn-ghost" style="padding:6px 14px;font-size:.75rem;border-color:rgba(255,79,129,.4);color:#ff4f81;">Tolak</button>
                                        </form>
                                    </div>
                                @elseif ($ps === 'verified' && ($payment->trip_status ?? '') === 'ongoing')
                                    <form action="{{ route('admin.trip_complete') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="booking_id" value="{{ $payment->booking_id }}">
                                        <button type="submit" class="btn-primary" style="padding:6px 14px;font-size:.75rem;">Selesai</button>
                                    </form>
                                @else
                                    <span style="color:var(--text-muted);font-size:.8rem;">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="dashboard-table-empty">Belum ada data pembayaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Popup bukti pembayaran --}}
    <div class="proof-modal" id="proofModal" aria-hidden="true">
        <div class="proof-modal-backdrop" data-proof-close></div>
        <div class="proof-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="proofModalTitle">
            <button type="button" class="proof-modal-x" data-proof-close aria-label="Tutup">&times;</button>
            <h3 id="proofModalTitle"></h3>
            <p id="proofModalMeta"></p>
            <div class="proof-modal-imgwrap">
                <img id="proofModalImg" alt="Bukti pembayaran">
            </div>
            <div class="proof-modal-actions">
                <a id="proofModalOpen" href="#" target="_blank" rel="noopener" class="btn-ghost" style="padding:8px 18px;font-size:.82rem;">Buka di tab baru</a>
                <button type="button" class="btn-ghost" data-proof-close style="padding:8px 18px;font-size:.82rem;">Tutup</button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal = document.getElementById('proofModal');
        if (!modal) return;

        var img = document.getElementById('proofModalImg');
        var title = document.getElementById('proofModalTitle');
        var meta = document.getElementById('proofModalMeta');
        var openLink = document.getElementById('proofModalOpen');

        function openProof(button) {
            var src = button.getAttribute('data-src');
            img.src = src;
            title.textContent = button.getAttribute('data-title') || 'Bukti pembayaran';
            meta.textContent = button.getAttribute('data-meta') || '';
            openLink.href = src;
            modal.classList.add('is-open');
            modal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('proof-open');
        }

        function closeProof() {
            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('proof-open');
            img.removeAttribute('src');
        }

        document.querySelectorAll('[data-proof-open]').forEach(function (button) {
            button.addEventListener('click', function () { openProof(button); });
        });

        modal.querySelectorAll('[data-proof-close]').forEach(function (el) {
            el.addEventListener('click', closeProof);
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && modal.classList.contains('is-open')) {
                closeProof();
            }
        });
    });
</script>
@endpush