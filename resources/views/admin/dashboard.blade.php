@extends('layouts.admin')

@section('title', 'Dashboard Admin - Wandee')

@section('content')

    {{-- HERO --}}
    <div class="admin-header-small">
        <div>
            <span class="admin-badge">Dashboard Admin</span>
            <h1>Selamat datang, {{ auth()->user()->name }}!</h1>
            <p>Kelola destinasi wisata, pengguna, transaksi perjalanan, dan seluruh aktivitas Wandee dari dashboard admin.</p>
        </div>

        <div class="admin-user">
            @if (auth()->user()->photo)
                <img src="{{ asset('uploads/profile/' . auth()->user()->photo) }}" alt="Foto admin">
            @else
                <div class="admin-user-empty">
                    <i data-lucide="user"></i>
                </div>
            @endif

            <div>
                <strong>{{ auth()->user()->name }}</strong>
                <p>{{ auth()->user()->email }}</p>
            </div>
        </div>
    </div>

    {{-- STATS --}}
    <div class="admin-stats">
        <div class="stat-card">
            <p>Pengguna Terdaftar</p>
            <h2>{{ number_format($total_users, 0, ',', '.') }}</h2>
            <span class="stat-growth">
                <i data-lucide="users"></i> Semua akun pengguna
            </span>
        </div>

        <div class="stat-card">
            <p>Destinasi Aktif</p>
            <h2>{{ number_format($total_trips, 0, ',', '.') }}</h2>
            <span class="stat-growth">
                <i data-lucide="map-pin"></i> Paket perjalanan
            </span>
        </div>

        <div class="stat-card">
            <p>Total Pemesanan</p>
            <h2>{{ number_format($total_bookings, 0, ',', '.') }}</h2>
            <span class="stat-growth">
                <i data-lucide="shopping-bag"></i> Transaksi masuk
            </span>
        </div>

        <div class="stat-card">
            <p>Total Pendapatan</p>
            <h2 style="font-size:clamp(1.6rem, 2.5vw, 3rem);">
                Rp {{ number_format($total_revenue, 0, ',', '.') }}
            </h2>
            <span class="stat-growth stat-growth-green">
                <i data-lucide="dollar-sign"></i> Pemesanan lunas
            </span>
        </div>
    </div>

    {{-- CHARTS --}}
    <div class="charts-grid">
        <div class="chart-card chart-card-custom">
            <h3 class="chart-card-title">
                <i data-lucide="trending-up" class="badge-small-icon"></i>
                Tren Pemesanan Bulanan
            </h3>
            <div class="chart-canvas-wrap">
                <canvas id="bookingsChart"></canvas>
            </div>
        </div>

        <div class="chart-card chart-card-custom">
            <h3 class="chart-card-title">
                <i data-lucide="pie-chart" class="badge-small-icon"></i>
                Kategori Destinasi
            </h3>
            <div class="chart-canvas-wrap">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>

    {{-- RECENT TRANSACTIONS --}}
    <div class="dashboard-table-card dashboard-table-card-custom">
        <h3 class="chart-card-title">
            <i data-lucide="clock" class="badge-small-icon"></i>
            Transaksi Pemesanan Terbaru
        </h3>

        <div class="dashboard-table-wrap">
            <table class="dashboard-table-custom">
                <thead>
                    <tr class="dashboard-table-tr-header">
                        <th class="dashboard-table-th">ID</th>
                        <th class="dashboard-table-th">Nama Pemesan</th>
                        <th class="dashboard-table-th">Destinasi</th>
                        <th class="dashboard-table-th">Jumlah Peserta</th>
                        <th class="dashboard-table-th">Total Harga</th>
                        <th class="dashboard-table-th">Pembayaran</th>
                        <th class="dashboard-table-th">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recent_bookings as $booking)
                        <tr class="dashboard-table-tr">
                            <td class="dashboard-table-td">#{{ $booking->id }}</td>
                            <td class="dashboard-table-td"><strong>{{ $booking->user_name ?? 'Guest' }}</strong></td>
                            <td class="dashboard-table-td-muted">{{ $booking->destination_title ?? '-' }}</td>
                            <td class="dashboard-table-td-muted">{{ $booking->total_people }} Orang</td>
                            <td class="dashboard-table-td-accent">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                            <td class="dashboard-table-td">
                                @php $payStatus = $booking->payment_status ?? 'pending'; @endphp
                                <span class="status-badge {{ $payStatus === 'paid' ? 'paid' : ($payStatus === 'cancelled' ? 'cancelled' : 'pending') }}">
                                    {{ $payStatus === 'paid' ? 'Paid' : ($payStatus === 'cancelled' ? 'Batal' : 'Pending') }}
                                </span>
                            </td>
                            <td class="dashboard-table-td-muted">
                                {{ \Carbon\Carbon::parse($booking->created_at)->format('d/m/Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="dashboard-table-empty">Belum ada transaksi pemesanan terbaru.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    window.wandeeDashboardData = {
        labels: @json($monthly_labels),
        bookings: @json($monthly_bookings),
        categories: @json($category_stats->pluck('category')),
        categoryTotals: @json($category_stats->pluck('total'))
    };
</script>
@endpush
