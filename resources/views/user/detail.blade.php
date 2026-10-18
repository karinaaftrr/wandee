@extends('layouts.app')

@section('title', ($dest->title ?? 'Destinasi') . ' - Wandee')
@section('body-class', 'detail-page')

@section('content')
@include('partials.navbar')

<main class="detail-shell">

    <a href="{{ url('/#destinations') }}" class="detail-back">
        <i data-lucide="arrow-left"></i>
        Kembali ke Jelajah
    </a>

    @if (session('booking_error'))
        <div class="booking-error-alert" style="background: rgba(255, 79, 129, 0.15); border: 1px solid rgba(255, 79, 129, 0.3); color: #ff4f81; padding: 18px 24px; border-radius: 20px; margin-bottom: 24px; font-weight: 600; display: flex; align-items: center; gap: 12px; max-width: 1240px; margin-left: auto; margin-right: auto;">
            <i data-lucide="alert-circle" style="width: 20px; height: 20px; flex-shrink: 0;"></i>
            <span>{{ session('booking_error') }}</span>
        </div>
    @endif

    @if (session('review_message'))
        <div style="background: rgba(57,255,120,0.12); border: 1px solid rgba(57,255,120,0.3); color: #39ff78; padding: 16px 24px; border-radius: 20px; margin-bottom: 24px; font-weight: 600; max-width: 1240px; margin-left: auto; margin-right: auto;">
            {{ session('review_message') }}
        </div>
    @endif

    <section class="detail-grid-new">

        <!-- KIRI -->
        <div class="detail-left-new">

            <article class="detail-destination-card">

                <div class="detail-image-wrap">

                    <form action="{{ route('user.favorite_toggle') }}" method="POST" class="favorite-overlay-form">
                        @csrf
                        <input type="hidden" name="destination_id" value="{{ $dest->id }}">
                        <button type="submit" class="favorite-overlay-btn {{ $isFavorite ? 'active' : '' }}">♥</button>
                    </form>

                    <img
                        src="{{ asset('assets/img/' . $dest->image) }}"
                        alt="{{ $dest->title }}"
                        onerror="this.src='{{ asset('assets/img/gunung.png') }}'">

                    @if ((int)$dest->quota > 0)
                        <span class="detail-open-badge">Perjalanan Terbuka</span>
                    @else
                        <span class="detail-open-badge detail-full-badge" style="background: rgba(255, 79, 129, 0.15); color: #ff4f81; border: 1px solid rgba(255, 79, 129, 0.3);">Kuota Penuh</span>
                    @endif
                    <span class="detail-rating-badge">★ {{ number_format((float)$dest->rating, 1) }}</span>
                </div>

                <div class="detail-destination-body">

                    <h1>{{ $dest->title }}</h1>

                    <p class="detail-rating-line">
                        <strong>{{ number_format((float)$dest->rating, 1) }}</strong>
                        <span>Open Trip</span>
                        <span>{{ $dest->category }}</span>
                    </p>

                    <div class="detail-info-strip-new">
                        <div>
                            <i data-lucide="map-pin"></i>
                            <span>Lokasi</span>
                            <strong>{{ $dest->location }}</strong>
                        </div>
                        <div>
                            <i data-lucide="calendar-days"></i>
                            <span>Tanggal</span>
                            <strong>{{ $dest->trip_date ?: '-' }}</strong>
                        </div>
                        <div>
                            <i data-lucide="tag"></i>
                            <span>Kategori</span>
                            <strong>{{ $dest->category }}</strong>
                        </div>
                    </div>

                </div>

            </article>

            <!-- Harga -->
            <section class="detail-price-card">
                <span>Harga Per Orang</span>
                <h2>Rp {{ number_format((int)$dest->price, 0, ',', '.') }}<small>/orang</small></h2>
                <p>{{ (int)$dest->quota }} slot tersedia</p>
            </section>

            <!-- Tentang -->
            <section class="detail-about-card">
                <h2>Tentang Perjalanan</h2>
                <p>{!! nl2br(e($dest->description ?: 'Deskripsi belum tersedia.')) !!}</p>
            </section>

            <section class="detail-panel detail-reviews-card detail-reviews-summary">
                <div>
                    <h2>
                        <i data-lucide="message-square"></i>
                        Ulasan Pengguna
                    </h2>
                    <p>{{ count($reviews) }} ulasan dari traveler Wandee</p>
                </div>

                <button type="button" class="review-open-button" id="openReviewComments">
                    Lihat ulasan
                    <i data-lucide="chevron-right"></i>
                </button>
            </section>

        </div>

        <!-- KANAN -->
        <div class="detail-right-new">

            <section class="detail-panel detail-itinerary-panel">
                <h2>
                    <i data-lucide="map"></i>
                    Rencana Perjalanan
                </h2>
                <div class="detail-timeline">
                    @foreach($itineraries as $item)
                        <article>
                            <span>{{ $item['label'] }}</span>
                            <h3>{{ $item['title'] }}</h3>
                            <p>{{ $item['desc'] }}</p>
                            <small>
                                <i data-lucide="clock-3"></i>
                                {{ $item['time'] }}
                            </small>
                        </article>
                    @endforeach
                </div>
            </section>

            <section class="detail-panel">
                <h2>Fasilitas</h2>
                <div class="detail-facilities">
                    @foreach($facilities as $fac)
                        @php $icon = $facility_icons[$fac] ?? 'check-circle'; @endphp
                        <span>
                            <i data-lucide="{{ $icon }}"></i>
                            {{ $fac }}
                        </span>
                    @endforeach
                </div>
            </section>

            <section class="detail-join-card">
                @if ((int)$dest->quota > 0)
                    <div class="qty-select-wrapper" style="margin-bottom: 15px; display: flex; align-items: center; justify-content: space-between; width: 100%; gap: 10px;">
                        <label for="ticketQty" style="font-size: 14px; font-weight: 500; color: #a0aec0;">Jumlah Peserta:</label>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <input type="number" id="ticketQty" value="1" min="1" max="{{ (int)$dest->quota }}" 
                                   style="width: 70px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #fff; border-radius: 8px; padding: 6px 10px; font-weight: 600; text-align: center;">
                            <span style="font-size: 12px; color: #718096;">/ {{ (int)$dest->quota }} slot</span>
                        </div>
                    </div>
                @endif
                <div style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 15px;">
                    <div>
                        <span>Total Harga</span>
                        <strong id="displayTotalPrice">Rp {{ number_format((int)$dest->price, 0, ',', '.') }}</strong>
                    </div>
                </div>
                @if ((int)$dest->quota > 0)
                    <a href="{{ route('user.payment', ['id' => $dest->id, 'people' => 1]) }}" id="btnJoinTrip" class="btn-primary">Ikut Perjalanan</a>
                @else
                    <button class="btn-primary" disabled style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); color: rgba(232, 240, 235, 0.32); cursor: not-allowed;">Kuota Penuh</button>
                @endif
            </section>

        </div>

    </section>

</main>

@include('partials.footer')

<div id="reviewCommentsModal" class="review-comments-modal" aria-hidden="true">
    <div class="review-comments-backdrop" data-close-review-comments></div>
    <section class="review-comments-panel" role="dialog" aria-modal="true" aria-labelledby="reviewCommentsTitle">
        <header class="review-comments-head">
            <div>
                <h2 id="reviewCommentsTitle">Ulasan Pengguna</h2>
                <p>{{ count($reviews) }} ulasan untuk {{ $dest->title }}</p>
            </div>
            <button type="button" class="review-comments-close" data-close-review-comments aria-label="Tutup ulasan">
                <i data-lucide="x"></i>
            </button>
        </header>

        @if($reviews->isNotEmpty())
            <div class="reviews-list">
                @foreach($reviews as $rev)
                    @php $isOwnReview = (int) $rev->user_id === (int) auth()->id(); @endphp
                    <article class="review-item {{ $isOwnReview ? 'is-own-review' : '' }}">
                        <div class="review-header">
                            <div class="review-user">
                                @if(!empty($rev->user?->photo))
                                    <img src="{{ asset('uploads/profile/' . $rev->user->photo) }}" alt="{{ $rev->user->name }}">
                                @else
                                    <div class="user-avatar-empty">
                                        <i data-lucide="user"></i>
                                    </div>
                                @endif

                                <div>
                                    <strong>{{ $rev->user?->name ?? 'User Wandee' }}</strong>
                                    <small>{{ $rev->created_at ? \Carbon\Carbon::parse($rev->created_at)->format('d M Y') : 'Baru saja' }}</small>
                                </div>
                            </div>

                            <div class="review-meta">
                                @if($isOwnReview)
                                    <span class="own-review-badge">Ulasan Anda</span>
                                @endif
                                <span class="review-rating">&#9733; {{ number_format((float)$rev->rating, 1) }}</span>
                            </div>
                        </div>

                        <div class="review-content">
                            <p>{!! nl2br(e($rev->review)) !!}</p>

                            @if(!empty($rev->review_image))
                                <div class="review-photo-wrap">
                                    <img
                                        src="{{ asset('uploads/reviews/' . $rev->review_image) }}"
                                        alt="Foto ulasan"
                                        onclick="openReviewLightbox(this.src)">
                                </div>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="reviews-empty">
                <i data-lucide="message-square-off"></i>
                <p>Belum ada ulasan untuk destinasi ini.</p>
            </div>
        @endif
    </section>
</div>

<div id="reviewLightbox" class="lightbox-modal">
    <span class="lightbox-close" aria-label="Tutup" role="button" tabindex="0">&times;</span>
    <img class="lightbox-content" id="lightboxImage" alt="Foto ulasan">
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const lightbox = document.getElementById('reviewLightbox');
        const lightboxImage = document.getElementById('lightboxImage');
        const closeButton = document.querySelector('.lightbox-close');
        const commentsModal = document.getElementById('reviewCommentsModal');
        const openCommentsButton = document.getElementById('openReviewComments');
        const closeCommentsButtons = document.querySelectorAll('[data-close-review-comments]');

        function openReviewComments() {
            if (!commentsModal) return;
            commentsModal.classList.add('is-open');
            commentsModal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('modal-open');
        }

        function closeReviewComments() {
            if (!commentsModal) return;
            commentsModal.classList.remove('is-open');
            commentsModal.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('modal-open');
        }

        function closeReviewLightbox() {
            if (!lightbox || !lightboxImage) return;
            lightbox.style.opacity = '0';
            lightboxImage.style.transform = 'scale(0.9)';
            setTimeout(function() {
                lightbox.style.display = 'none';
            }, 300);
        }

        window.openReviewLightbox = function(src) {
            if (!lightbox || !lightboxImage) return;
            lightboxImage.src = src;
            lightbox.style.display = 'flex';
            lightbox.offsetHeight;
            lightbox.style.opacity = '1';
            lightboxImage.style.transform = 'scale(1)';
        };

        if (closeButton) {
            closeButton.addEventListener('click', closeReviewLightbox);
        }

        if (lightbox) {
            lightbox.addEventListener('click', function(event) {
                if (event.target === lightbox) {
                    closeReviewLightbox();
                }
            });
        }

        if (openCommentsButton) {
            openCommentsButton.addEventListener('click', openReviewComments);
        }

        if (new URLSearchParams(window.location.search).get('buka_ulasan') === '1') {
            openReviewComments();
        }

        // Dynamic quantity calculation
        const ticketQty = document.getElementById('ticketQty');
        const displayTotalPrice = document.getElementById('displayTotalPrice');
        const btnJoinTrip = document.getElementById('btnJoinTrip');
        const basePrice = {{ (int)$dest->price }};
        const destinationId = {{ (int)$dest->id }};
        const maxQuota = {{ (int)$dest->quota }};

        if (ticketQty && displayTotalPrice && btnJoinTrip) {
            function updatePrice() {
                let qty = parseInt(ticketQty.value);
                if (isNaN(qty) || qty < 1) {
                    qty = 1;
                }
                if (qty > maxQuota) {
                    qty = maxQuota;
                    ticketQty.value = maxQuota;
                }
                
                const total = basePrice * qty;
                displayTotalPrice.textContent = 'Rp ' + total.toLocaleString('id-ID');
                btnJoinTrip.href = '{{ route("user.payment", ["id" => $dest->id]) }}?people=' + qty;
            }

            ticketQty.addEventListener('input', updatePrice);
            ticketQty.addEventListener('change', function() {
                let qty = parseInt(ticketQty.value);
                if (isNaN(qty) || qty < 1) {
                    qty = 1;
                    ticketQty.value = 1;
                }
                updatePrice();
            });
        }

        closeCommentsButtons.forEach(function(button) {
            button.addEventListener('click', closeReviewComments);
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && lightbox && lightbox.style.display === 'flex') {
                closeReviewLightbox();
            } else if (event.key === 'Escape' && commentsModal && commentsModal.classList.contains('is-open')) {
                closeReviewComments();
            }
        });

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endsection