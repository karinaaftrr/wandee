@extends('layouts.admin')

@section('title', 'Verifikasi Pembayaran - Wandee')

@section('content')

    <div class="admin-header-small">
        <div>
            <span class="admin-badge">Verifikasi Pembayaran</span>
            <h1>Manage Pembayaran</h1>
            <p>Verifikasi atau tolak bukti pembayaran dari pengguna.</p>
        </div>
    </div>

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
                        <th class="dashboard-table-th">Status</th>
                        <th class="dashboard-table-th">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payments as $payment)
                        <tr class="dashboard-table-tr">
                            <td class="dashboard-table-td">#{{ $payment->id }}</td>
                            <td class="dashboard-table-td"><strong>{{ $payment->user_name ?? 'Guest' }}</strong></td>
                            <td class="dashboard-table-td-muted">{{ $payment->destination_title ?? '-' }}</td>
                            <td class="dashboard-table-td-muted">{{ $payment->total_people ?? 0 }} Orang</td>
                            <td class="dashboard-table-td-accent">Rp {{ number_format($payment->payment_amount ?? 0, 0, ',', '.') }}</td>
                            <td class="dashboard-table-td-muted">{{ $payment->payment_method ?? '-' }}</td>
                            <td class="dashboard-table-td">
                                @php $ps = $payment->payment_status ?? 'waiting'; @endphp
                                <span class="status-badge {{ $ps === 'verified' ? 'paid' : ($ps === 'rejected' ? 'cancelled' : 'pending') }}">
                                    {{ $ps === 'verified' ? 'Verified' : ($ps === 'rejected' ? 'Rejected' : 'Waiting') }}
                                </span>
                            </td>
                            <td class="dashboard-table-td">
                                @if ($ps === 'waiting')
                                    <div style="display:flex;gap:6px;flex-wrap:wrap;">
                                        <form action="{{ route('admin.payment_update') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="payment_id" value="{{ $payment->id }}">
                                            <input type="hidden" name="status" value="verified">
                                            <button type="submit" class="btn-primary" style="padding:6px 14px;font-size:.75rem;">Verifikasi</button>
                                        </form>
                                        <button type="button" class="btn-ghost js-reject-btn"
                                            data-payment-id="{{ $payment->id }}"
                                            data-payer="{{ $payment->user_name ?? 'Guest' }}"
                                            style="padding:6px 14px;font-size:.75rem;border-color:rgba(255,79,129,.4);color:#ff4f81;">Tolak</button>
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
                            <td colspan="8" class="dashboard-table-empty">Belum ada data pembayaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL ALASAN PENOLAKAN --}}
    <div class="logout-modal" id="rejectModal" aria-hidden="true">
        <div class="logout-modal-backdrop" data-reject-cancel></div>
        <form class="logout-dialog reject-dialog" id="rejectForm" action="{{ route('admin.payment_update') }}" method="POST" role="dialog" aria-modal="true" aria-labelledby="rejectTitle">
            @csrf
            <input type="hidden" name="payment_id" id="rejectPaymentId">
            <input type="hidden" name="status" value="rejected">
            <h3 id="rejectTitle">Tolak pembayaran</h3>
            <p id="rejectDesc">Tulis alasan penolakan agar pemesan tahu apa yang perlu diperbaiki.</p>
            <textarea name="rejection_reason" id="rejectReason" maxlength="500" placeholder="Contoh: Bukti transfer tidak jelas / nominal tidak sesuai"></textarea>
            <div class="reject-error" id="rejectError">Alasan penolakan wajib diisi.</div>
            <div class="logout-actions">
                <button type="button" class="logout-cancel" data-reject-cancel>Batal</button>
                <button type="submit" class="logout-confirm is-danger">Konfirmasi Tolak</button>
            </div>
        </form>
    </div>

    <script>
        (function () {
            var modal = document.getElementById('rejectModal');
            var form = document.getElementById('rejectForm');
            var reason = document.getElementById('rejectReason');
            var error = document.getElementById('rejectError');

            function open(id, payer) {
                document.getElementById('rejectPaymentId').value = id;
                document.getElementById('rejectDesc').textContent =
                    'Tulis alasan menolak pembayaran dari ' + payer + '. Pesanan akan ditolak setelah kamu konfirmasi.';
                reason.value = '';
                error.classList.remove('show');
                modal.classList.add('is-open');
                modal.setAttribute('aria-hidden', 'false');
                setTimeout(function () { reason.focus(); }, 50);
            }

            function close() {
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');
            }

            document.querySelectorAll('.js-reject-btn').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    open(btn.dataset.paymentId, btn.dataset.payer);
                });
            });

            modal.querySelectorAll('[data-reject-cancel]').forEach(function (el) {
                el.addEventListener('click', close);
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') close();
            });

            form.addEventListener('submit', function (e) {
                if (!reason.value.trim()) {
                    e.preventDefault();
                    error.classList.add('show');
                    reason.focus();
                }
            });
        })();
    </script>

@endsection
