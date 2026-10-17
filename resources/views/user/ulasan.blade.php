@extends('layouts.app')

@section('title', 'Ulasan - Wandee')
@section('body-class', 'review-page')

@section('content')
@include('partials.navbar')

<main class="review-shell">

    <section class="review-layout-new">

        <div class="review-side">

            <h1>Beri Ulasan</h1>

            <p>Ceritakan perjalanan luar biasa kamu kepada komunitas penjelajah kami.</p>

            @if(session('review_message'))
                <div class="alert alert-success">{{ session('review_message') }}</div>
            @endif

            @if(session('review_error'))
                <div class="alert alert-danger">{{ session('review_error') }}</div>
            @endif

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
                <input type="hidden" name="rating" id="ratingValue" value="0">

                <div class="review-label-row">
                    <label for="destination_id">Pilih perjalanan selesai</label>
                </div>

                <select
                    id="destination_id"
                    name="destination_id"
                    class="review-select"
                    {{ $eligibleBookings->isEmpty() ? 'disabled' : '' }}
                    required>
                    @if($eligibleBookings->isEmpty())
                        <option value="">Belum ada destinasi yang bisa diulas</option>
                    @else
                        @foreach($eligibleBookings as $booking)
                            <option value="{{ $booking->destination_id }}">
                                {{ $booking->destination->title }} - {{ $booking->destination->location }}
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
                    required></textarea>

                <label class="review-upload">
                    <input type="file" id="reviewPhoto" name="review_photo" accept="image/png,image/jpeg,image/jpg" {{ $eligibleBookings->isEmpty() ? 'disabled' : '' }}>
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
                    {{ $eligibleBookings->isEmpty() ? 'disabled' : '' }}>
                    {{ $eligibleBookings->isEmpty() ? 'Belum Bisa Mengulas' : 'Kirim Ulasan' }}
                    <i data-lucide="send"></i>
                </button>
            </form>

        </section>

    </section>

</main>

@include('partials.footer')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ratingInput = document.getElementById('ratingValue');
        const ratingButtons = document.querySelectorAll('.rating-stars button');
        
        ratingButtons.forEach((button) => {
            button.addEventListener('click', () => {
                const rating = Number(button.dataset.rating);
                ratingInput.value = rating;
                ratingButtons.forEach((item) => {
                    item.classList.toggle('active', Number(item.dataset.rating) <= rating);
                });
            });
        });

        const reviewPhotoInput = document.getElementById('reviewPhoto');
        const selectedFileName = document.getElementById('selectedFileName');

        if (reviewPhotoInput && selectedFileName) {
            reviewPhotoInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    selectedFileName.textContent = 'File dipilih: ' + this.files[0].name;
                } else {
                    selectedFileName.textContent = '';
                }
            });
        }

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endsection
