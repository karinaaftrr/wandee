@extends('layouts.app')

@section('title', 'Dasbor Pengguna - Wandee')
@section('body-class', 'dashboard-page')

@section('content')
@include('partials.navbar')

{{-- HERO SECTION --}}
<section class="hero dashboard-hero">
    <div class="hero-bg">
        <img src="{{ asset('assets/img/gunung.png') }}" alt="Pemandangan pegunungan" class="hero-img">
        <div class="hero-overlay"></div>
    </div>

    <div class="hero-content dashboard-hero-content">
        <p class="dashboard-welcome">
            Hai, {{ $name ?? auth()->user()->name }}
        </p>

        <p class="hero-eyebrow">
            Jelajahi Keindahan Dunia Bersama
            <span class="accent">Wandee</span>
        </p>

        <h1 class="hero-title">
            Temukan destinasi eksotis,<br>
            petualangan tak terlupakan,<br>
            dan momen berharga.
        </h1>

        <div class="hero-cta">
            <a href="#destinations" class="btn-primary btn-lg">
                Mulai Eksplorasi
            </a>

            <a href="{{ route('user.riwayat') }}" class="btn-ghost btn-lg">
                Lihat Perjalanan Saya
            </a>
        </div>

        <div class="search-bar">
            <div class="search-field search-destination">
                <input type="text" id="searchInput" placeholder="Mau pergi ke mana?">
            </div>

            <div class="search-divider"></div>

            <button type="button" class="btn-search" aria-label="Cari">
                <i data-lucide="search"></i>
            </button>
        </div>
    </div>
</section>

{{-- CATEGORIES --}}
<section class="categories">
    <div class="container">
        <div class="cat-grid">
            <a href="#destinations" class="cat-card" data-cat="Gunung">
                <div class="cat-icon">
                    <i data-lucide="mountain"></i>
                </div>
                <span>Gunung</span>
            </a>

            <a href="#destinations" class="cat-card" data-cat="Pantai">
                <div class="cat-icon">
                    <i data-lucide="waves"></i>
                </div>
                <span>Pantai</span>
            </a>

            <a href="#destinations" class="cat-card" data-cat="Air Terjun">
                <div class="cat-icon">
                    <i data-lucide="droplets"></i>
                </div>
                <span>Air Terjun</span>
            </a>

            <a href="#destinations" class="cat-card" data-cat="Kota">
                <div class="cat-icon">
                    <i data-lucide="building-2"></i>
                </div>
                <span>Kota</span>
            </a>
        </div>
    </div>
</section>

{{-- DESTINATIONS --}}
<section class="recommended" id="destinations">
    <div class="container">
        <div class="section-header">
            <div>
                <h2 class="section-title">Rekomendasi Untukmu</h2>
                <p class="section-sub">
                    Pilihan trip terbaik yang siap menemani perjalananmu.
                </p>
            </div>

            <a href="{{ route('user.favorite') }}" class="view-all">
                Lihat Favorit
            </a>
        </div>

        @if(session('success'))
            <p class="success-message" style="color: #39ff78; margin-bottom: 20px;">
                {{ session('success') }}
            </p>
        @endif

        <div class="dest-grid">
            @forelse ($destinations as $dest)
                @php
                    $destId = $dest->id;
                    $displayRating = !empty($ratingStats[$destId]->total_reviews)
                        ? number_format((float)$ratingStats[$destId]->avg_rating, 1)
                        : number_format((float)($dest->rating ?? 5.0), 1);
                    $isFavorite = in_array($destId, $favoriteDestinationIds ?? []);
                @endphp

                <article class="dest-card" data-category="{{ $dest->category }}">
                    <div class="dest-thumb">
                        <a href="{{ route('user.detail', $destId) }}" class="dest-thumb-link" aria-label="Lihat detail {{ $dest->title }}">
                            <img src="{{ asset('assets/img/' . $dest->image) }}"
                                 alt="{{ $dest->title }}"
                                 class="dest-img"
                                 onerror="this.src='{{ asset('assets/img/gunung.png') }}'" />
                        </a>

                        <div class="dest-badge">★ {{ $displayRating }}</div>

                        <form action="{{ route('user.favorite_toggle') }}" method="POST" class="dest-favorite-form">
                            @csrf
                            <input type="hidden" name="destination_id" value="{{ $destId }}">
                            <button type="submit" class="dest-favorite-btn {{ $isFavorite ? 'is-active' : '' }}" aria-label="{{ $isFavorite ? 'Hapus dari favorit' : 'Tambah ke favorit' }}">
                                <i data-lucide="heart"></i>
                            </button>
                        </form>

                        <div class="dest-tags">
                            @if ((int)($dest->quota ?? 0) > 0)
                                <span class="tag">Perjalanan Terbuka</span>
                            @else
                                <span class="tag tag-full" style="background: rgba(255, 79, 129, 0.15); color: #ff4f81;">Kuota Penuh</span>
                            @endif
                        </div>
                    </div>

                    <div class="dest-body">
                        <h3 class="dest-name">
                            <a href="{{ route('user.detail', $destId) }}">{{ $dest->title }}</a>
                        </h3>

                        <div class="dest-loc">
                            <i data-lucide="map-pin"></i>
                            {{ $dest->location }}
                        </div>

                        <div class="dest-footer">
                            <div class="dest-date">
                                {{ $dest->trip_date ?: '-' }}
                            </div>

                            <div class="dest-price">
                                <span class="per-person">Per Orang</span>
                                <span class="price">Rp {{ number_format((int)$dest->price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="dest-empty">
                    Belum ada destinasi tersedia.
                </div>
            @endforelse
        </div>
    </div>
</section>

@include('partials.footer')
@endsection
