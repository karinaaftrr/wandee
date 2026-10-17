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
                            <td colspan="8" class="dashboard-table-empty">Belum ada data pembayaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
