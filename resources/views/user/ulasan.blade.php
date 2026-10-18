@extends('layouts.app')

@section('title', 'Ulasan - Wandee')
@section('body-class', 'review-page')

@section('content')
@include('partials.navbar')

{{-- ====== TOAST NOTIFICATION ====== --}}
@if(session('review_message') || session('review_error'))
<div id="reviewToast" style="
    position: fixed;
    top: 90px;
    left: 50%;
    transform: translateX(-50%) translateY(-20px);
    z-index: 9999;
    min-width: 320px;
    max-width: 520px;
    background: {{ session('review_message') ? 'rgba(14,28,18,0.97)' : 'rgba(28,10,10,0.97)' }};
    border: 1px solid {{ session('review_message') ? 'rgba(57,255,120,0.4)' : 'rgba(248,113,113,0.4)' }};
    border-radius: 16px;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    box-shadow: 0 8px 40px rgba(0,0,0,0.5), 0 0 0 1px {{ session('review_message') ? 'rgba(57,255,120,0.08)' : 'rgba(248,113,113,0.08)' }};
    opacity: 0;
    transition: opacity 0.35s ease, transform 0.35s cubic-bezier(.4,0,.2,1);
    backdrop-filter: blur(16px);
">
    <div style="
        width: 36px; height: 36px; flex-shrink: 0;
        border-radius: 50%;
        background: {{ session('review_message') ? 'rgba(57,255,120,0.15)' : 'rgba(248,113,113,0.15)' }};
        display: flex; align-items:center; justify-content:center;
    ">
        @if(session('review_message'))
            <i data-lucide="check" style="width:18px;height:18px;color:#39ff78;"></i>
        @else
            <i data-lucide="x" style="width:18px;height:18px;color:#f87171;"></i>
        @endif
    </div>
    <div style="flex:1;">
        <p style="font-size:14px; font-weight:700; color:{{ session('review_message') ? '#39ff78' : '#f87171' }}; margin-bottom:2px;">
            {{ session('review_message') ? 'Ulasan Berhasil Dikirim! 🎉' : 'Gagal Mengirim Ulasan' }}
        </p>
        <p style="font-size:13px; color:rgba(232,240,235,0.7); line-height:1.5;">
            {{ session('review_message') ?? session('review_error') }}
        </p>
    </div>
    <button onclick="document.getElementById('reviewToast').style.opacity='0'; setTimeout(()=>this.closest('#reviewToast').remove(),350);" style="
        background: none; border: none; cursor: pointer;
        color: rgba(232,240,235,0.4); padding: 4px;
        display:flex; align-items:center;
    ">
        <i data-lucide="x" style="width:16px;height:16px;"></i>
    </button>
</div>
@endif

<main class="review-shell">

    <section class="review-layout-new">

        <div class="review-side">

            <h1>Beri Ulasan</h1>

            <p>Ceritakan perjalanan luar biasa kamu kepada komunitas penjelajah kami.</p>

            {{-- Pesan ditampilkan via toast di atas, tidak perlu duplikasi di sini --}}

            @if($selectedBooking)
                <article class="review-trip-preview">
                    <div class="review-trip-image">
                        <img src="{{ asset('assets/img/' . ($selectedBooking->destination->image ?? 'gunung.png')) }}" alt="{{ $selectedBooking->destination->title }}">
                        <span>{{ number_format((float)($selectedBooking->destination->rating ?? 5.0), 1) }}</span>
                    </div>

                    <div>
                        <small>
                            <i data-lucide="map-pin"></i>
                            {{ $selectedBooking->destination->location }}
                        </small>

                        <h2>{{ $selectedBooking->destination->title }}</h2>

                        <p>
                            <i data-lucide="calendar-days"></i>
                            {{ $selectedBooking->destination->trip_date ?: 'Perjalanan selesai' }}
                        </p>
                    </div>
                </article>
            @else
                <article class="review-trip-preview">
                    <div class="review-trip-image">
                        <img src="{{ asset('assets/img/gunung.png') }}" alt="Belum ada perjalanan selesai">
                        <span>5.0</span>
                    </div>
                    <div>
                        <small><i data-lucide="lock"></i> Belum tersedia</small>
                        <h2>Belum ada trip selesai</h2>
                        <p><i data-lucide="info"></i> Ulasan terbuka setelah perjalanan berstatus selesai.</p>
                    </div>
                </article>
            @endif

            <blockquote class="review-quote">
                "Setiap perjalanan memiliki cerita. Kata-kata Anda dapat menginspirasi petualangan berikutnya bagi orang lain."
            </blockquote>

        </div>

        <section class="review-form-card review-form-card-new">

            <div class="review-form-head">
                <h2>Bagaimana pengalaman Anda?</h2>
                <p>Pilih rating Anda (1-5 bintang)</p>
            </div>

            <div class="rating-stars" aria-label="Pilih rating">
                <button type="button" aria-label="1 bintang" data-rating="1">
                    <i data-lucide="star"></i>
                </button>
                <button type="button" aria-label="2 bintang" data-rating="2">
                    <i data-lucide="star"></i>
                </button>
                <button type="button" aria-label="3 bintang" data-rating="3">
                    <i data-lucide="star"></i>
                </button>
                <button type="button" aria-label="4 bintang" data-rating="4">
                    <i data-lucide="star"></i>
                </button>
                <button type="button" aria-label="5 bintang" data-rating="5">
                    <i data-lucide="star"></i>
                </button>
            </div>

            
    <form action="{{ route('user.ulasan.submit') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="rating" id="ratingValue" value="{{ old('rating', 0) }}">
        <input type="hidden" name="booking_id" id="bookingId" value="{{ $selectedBooking->id ?? '' }}">

        <div class="review-label-row">
            <label for="destination_id">Pilih perjalanan selesai</label>
        </div>

        <select
            id="destination_id"
            name="destination_id"
            class="review-select"
            {{ $eligibleBookings->isEmpty() ? 'disabled' : '' }}
            required
        >
            @if($eligibleBookings->isEmpty())
                <option value="">Belum ada destinasi yang bisa diulas</option>
            @else
                @foreach($eligibleBookings as $booking)
                    <option
                        value="{{ $booking->destination_id }}"
                        data-booking-id="{{ $booking->id }}"
                        {{ $selectedBooking && $booking->id == $selectedBooking->id ? 'selected' : '' }}
                    >
                        {{ $booking->destination->title }}
                        - {{ $booking->destination->location }}
                        (Pemesanan #{{ $booking->id }})
                    </option>
                @endforeach
            @endif
        </select>

        <div class="review-label-row">
            <label for="reviewText">Tulis ulasan Anda</label>
            <span>Maksimal 500 karakter</span>
        </div>

        <textarea
            id="reviewText"
            name="review"
            class="review-textarea"
            maxlength="500"
            placeholder="Bagikan detail tentang pemandu, akomodasi, pemandangan, dan lainnya..."
            {{ $eligibleBookings->isEmpty() ? 'disabled' : '' }}
            required
        >{{ old('review') }}</textarea>

        <label class="review-upload">
            <input
                type="file"
                id="reviewPhoto"
                name="review_photo"
                accept="image/png,image/jpeg,image/jpg"
                {{ $eligibleBookings->isEmpty() ? 'disabled' : '' }}
            >
            <span>
                <i data-lucide="upload-cloud"></i>
            </span>
            <strong>Klik untuk unggah atau seret foto ke sini</strong>
            <div id="selectedFileName" style="margin-top:8px;font-size:13px;color:#4ade80;"></div>
            <small>PNG, JPG (Maks. 5MB)</small>
        </label>

        <button
            type="submit"
            class="btn-primary review-submit"
            {{ $eligibleBookings->isEmpty() ? 'disabled' : '' }}
        >
            {{ $eligibleBookings->isEmpty() ? 'Belum Bisa Mengulas' : 'Kirim Ulasan' }}
            <i data-lucide="send"></i>
        </button>
    </form>


        </section>

    </section>

</main>

@include('partials.footer')


<script>
    document.addEventListener('DOMContentLoaded', function () {
        // =========================
        // RATING BINTANG
        // =========================
        const ratingInput = document.getElementById('ratingValue');
        const ratingButtons = document.querySelectorAll('.rating-stars button');

        function updateRating(rating) {
            if (!ratingInput) return;

            ratingInput.value = rating;

            ratingButtons.forEach((button) => {
                button.classList.toggle(
                    'active',
                    Number(button.dataset.rating) <= rating
                );
            });
        }

        ratingButtons.forEach((button) => {
            button.addEventListener('click', function () {
                updateRating(Number(this.dataset.rating));
            });
        });

        // Pertahankan rating jika form dikirim kembali karena validasi gagal
        if (ratingInput && Number(ratingInput.value) > 0) {
            updateRating(Number(ratingInput.value));
        }

        // =========================
        // SINKRONISASI BOOKING ID
        // =========================
        const destinationSelect = document.getElementById('destination_id');
        const bookingIdInput = document.getElementById('bookingId');

        function syncSelectedBooking() {
            if (!destinationSelect || !bookingIdInput) return;

            const selectedOption = destinationSelect.selectedOptions[0];

            bookingIdInput.value = selectedOption
                ? (selectedOption.dataset.bookingId || '')
                : '';
        }

        if (destinationSelect && bookingIdInput) {
            destinationSelect.addEventListener(
                'change',
                syncSelectedBooking
            );

            syncSelectedBooking();
        }

        // =========================
        // UPLOAD FOTO ULASAN
        // =========================
        const reviewPhotoInput = document.getElementById('reviewPhoto');
        const selectedFileName = document.getElementById('selectedFileName');

        if (reviewPhotoInput && selectedFileName) {
            reviewPhotoInput.addEventListener('change', function () {
                if (this.files && this.files.length > 0) {
                    selectedFileName.textContent =
                        'File dipilih: ' + this.files[0].name;
                } else {
                    selectedFileName.textContent = '';
                }
            });
        }

        // =========================
        // IKON LUCIDE
        // =========================
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // =========================
        // TOAST NOTIFICATION
        // =========================
        const toast = document.getElementById('reviewToast');

        if (toast) {
            requestAnimationFrame(() => {
                toast.style.opacity = '1';
                toast.style.transform =
                    'translateX(-50%) translateY(0)';
            });

            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform =
                    'translateX(-50%) translateY(-20px)';

                setTimeout(() => {
                    if (toast.isConnected) {
                        toast.remove();
                    }
                }, 400);
            }, 4500);
        }
    });
</script>
@endsection

