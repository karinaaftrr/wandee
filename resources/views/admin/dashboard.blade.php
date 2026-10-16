
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Wandee</title>

    @vite([
    'resources/css/styles.css',
    'resources/js/script.js'
    ])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="admin-page">

<div class="admin-wrap">

    <aside class="admin-sidebar">
        <a href="{{ route('admin.dashboard') }}" class="admin-logo" style="text-decoration:none;">
            Wandee<span style="color:#fff;">.</span>
        </a>

        <nav class="admin-menu">
            <a href="{{ route('admin.dashboard') }}" class="active">
                <i data-lucide="layout-dashboard"></i>
                <span>Dashboard</span>
            </a>

            <a href="#destinasi">
                <i data-lucide="map-pin"></i>
                <span>Destinasi</span>
            </a>

            <a href="#pemesanan">
                <i data-lucide="shopping-bag"></i>
                <span>Pemesanan</span>
            </a>

            <a href="#pengguna">
                <i data-lucide="users"></i>
                <span>Pengguna</span>
            </a>

            <a href="#laporan">
                <i data-lucide="chart-no-axes-combined"></i>
                <span>Laporan</span>
            </a>

            <form action="{{ route('logout') }}" method="POST" style="margin-top:20px;">
                @csrf
                <button type="submit" style="width:100%;display:flex;align-items:center;gap:14px;padding:16px 18px;border:0;border-radius:18px;background:transparent;color:rgba(232,240,235,.58);font:inherit;cursor:pointer;text-align:left;">
                    <i data-lucide="log-out"></i>
                    <span>Keluar</span>
                </button>
            </form>
        </nav>
    </aside>

    <main class="admin-main">

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
                            <tr>
                                <td>{{ $booking->id }}</td>
                                <td>{{ $booking->user_name ?? 'Pengguna tidak ditemukan' }}</td>
                                <td>{{ $booking->destination_title ?? 'Destinasi tidak ditemukan' }}</td>
                                <td>{{ $booking->total_people }} orang</td>
                                <td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                <td>{{ ucfirst($booking->payment_status) }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($booking->created_at)->format('d/m/Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align:center;padding:24px;">
                                    Belum ada transaksi pemesanan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>

</body>
</html>
