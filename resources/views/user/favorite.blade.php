@extends('layouts.app')

@section('title', 'Favorit - Wandee')
@section('body-class', 'favorite-page')

@section('content')
@include('partials.navbar')

<main class="favorite-wrap">

    <div class="favorite-hero">
        <span class="favorite-kicker">
            <i data-lucide="heart"></i>
            Favorit Saya
        </span>
        <h1>Destinasi Favorit</h1>
        <p>Kelola destinasi favorit dan simpan perjalanan impian Anda.</p>
    </div>

    @if($favorites->isEmpty())
        <div class="favorite-add">
            <div style="text-align:center; padding: 48px; background: var(--bg-card); border-radius: var(--radius-md); border: 1px solid var(--border);">
                <p style="margin-bottom:16px; color: var(--text-muted);">Anda belum menambahkan destinasi ke favorit.</p>
                <a href="{{ url('/#destinations') }}" class="btn-primary">Telusuri Destinasi</a>
            </div>
        </div>
    @else
        <div class="favorite-grid">
            @foreach($favorites as $favorite)
                @php $dest = $favorite->destination; @endphp
                @if($dest)
                    <article class="favorite-card">
                        <div class="favorite-thumb">
                            <img
                                src="{{ asset('assets/img/' . $dest->image) }}"
                                alt="{{ $dest->title }}"
                                onerror="this.src='{{ asset('assets/img/gunung.png') }}'">
                            <span class="favorite-country">
                                {{ $dest->location }}
                            </span>
                        </div>

                        <div class="favorite-body">
                            <h3>{{ $dest->title }}</h3>
                            <p>{{ $dest->location }}</p>
                            <div class="favorite-price">
                                <strong>Rp {{ number_format((int)$dest->price, 0, ',', '.') }}</strong>
                                <a href="{{ route('user.detail', $dest->id) }}" class="favorite-detail">
                                    Lihat Detail
                                    <i data-lucide="arrow-right"></i>
                                </a>
                            </div>
                            <form action="{{ route('user.favorite_toggle') }}" method="POST" style="margin-top:16px;">
                                @csrf
                                <input type="hidden" name="destination_id" value="{{ $dest->id }}">
                                <button type="submit" class="btn-ghost" style="width:100%;">Hapus Favorit</button>
                            </form>
                        </div>
                    </article>
                @endif
            @endforeach
        </div>
    @endif

</main>

@include('partials.footer')
@endsection
