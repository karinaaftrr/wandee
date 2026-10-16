
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dasbor Pengguna - Wandee</title>

    @vite([
    'resources/css/styles.css',
    'resources/js/script.js'
    ])

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="dashboard-page">

    @include('partials.navbar')

    <main>
        <section class="hero dashboard-hero">
            <div class="hero-content">
                <p>Hai, {{ auth()->user()->name ?? 'Pengguna' }}!</p>

                <h1>
                    Temukan destinasi eksotis, petualangan tak terlupakan,
                    dan momen berharga.
                </h1>

                <p>
                    Jelajahi berbagai destinasi menarik dan rencanakan
                    perjalananmu bersama Wandee.
                </p>

                <div class="hero-actions">
                    <a href="#destinations" class="btn-primary">
                        Mulai Eksplorasi
                    </a>

                    <a href="#destinations" class="btn-secondary">
                        Lihat Destinasi
                    </a>
                </div>

                <div class="search-destination">
                    <input
                        type="text"
                        id="destinationSearch"
                        placeholder="Cari destinasi atau lokasi..."
                        aria-label="Cari destinasi"
                    >

                    <button type="button" class="btn-search">
                        <i data-lucide="search"></i>
                        Cari
                    </button>
                </div>
            </div>
        </section>

        <section class="categories section-container">
            <div class="section-heading">
                <h2>Jelajahi Berdasarkan Kategori</h2>
                <p>
                    Temukan destinasi sesuai petualangan yang kamu inginkan.
                </p>
            </div>

            <div class="category-list">
                <button type="button" class="cat-card active" data-cat="semua">
                    Semua
                </button>

                <button type="button" class="cat-card" data-cat="gunung">
                    Gunung
                </button>

                <button type="button" class="cat-card" data-cat="pantai">
                    Pantai
                </button>

                <button type="button" class="cat-card" data-cat="air terjun">
                    Air Terjun
                </button>

                <button type="button" class="cat-card" data-cat="kota">
                    Kota
                </button>
            </div>
        </section>

        <section class="recommended section-container" id="destinations">
            <div class="section-heading">
                <h2>Rekomendasi Destinasi</h2>
                <p>
                    Temukan tempat menarik untuk perjalanan berikutnya.
                </p>
            </div>

            @if (session('success'))
                <p class="success-message">
                    {{ session('success') }}
                </p>
            @endif

            <div class="dest-grid" id="destinationGrid">
                @forelse ($destinations as $destination)
                    <article
                        class="dest-card"
                        data-category="{{ strtolower($destination->category ?? '') }}"
                    >
                        <div class="dest-image">
                            <img
                                src="{{ asset('assets/img/' . $destination->image) }}"
                                alt="{{ $destination->title }}"
                                onerror="this.onerror=null;this.src='{{ asset('assets/img/gunung.png') }}'"
                            >

                            <form
                                method="POST"
                                action="{{ route('user.favorites.toggle') }}"
                            >
                                @csrf

                                <input
                                    type="hidden"
                                    name="destination_id"
                                    value="{{ $destination->id }}"
                                >

                                <button
                                    type="submit"
                                    class="favorite-btn {{ in_array($destination->id, $favoriteDestinationIds) ? 'favorited' : '' }}"
                                    aria-label="Tambah atau hapus favorit"
                                >
                                    <i data-lucide="heart"></i>
                                </button>
                            </form>
                        </div>

                        <div class="dest-content">
                            <div class="dest-rating">
                                <i data-lucide="star"></i>

                                <span>
                                    {{ number_format((float) ($ratingStats[$destination->id]->avg_rating ?? $destination->rating ?? 5), 1) }}
                                </span>

                                <span>
                                    ({{ $ratingStats[$destination->id]->total_reviews ?? 0 }}
                                    ulasan)
                                </span>
                            </div>

                            <h3 class="dest-name">
                                {{ $destination->title }}
                            </h3>

                            <p class="dest-loc">
                                <i data-lucide="map-pin"></i>
                                {{ $destination->location }}
                            </p>

                            <p class="dest-date">
                                {{ $destination->trip_date ?? 'Jadwal menyesuaikan' }}
                            </p>

                            <div class="dest-footer">
                                <strong>
                                    @if (is_numeric($destination->price))
                                        Rp {{ number_format((float) $destination->price, 0, ',', '.') }}
                                    @else
                                        {{ $destination->price }}
                                    @endif
                                </strong>

                                <span class="dest-status">
                                    {{ ($destination->quota ?? 0) > 0 ? 'Tersedia' : 'Penuh' }}
                                </span>
                            </div>
                        </div>
                    </article>
                @empty
                    <p>Belum ada destinasi yang tersedia.</p>
                @endforelse
            </div>
        </section>
    </main>

    @include('partials.footer')
</body>
</html>
