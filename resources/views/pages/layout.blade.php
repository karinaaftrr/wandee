@extends('layouts.app')

@section('body-class', 'static-page')

@push('styles')
<style>
    .static-wrap {
        max-width: 900px;
        margin: 0 auto;
        padding: 140px 24px 100px;
    }

    .static-hero {
        margin-bottom: 32px;
    }

    .static-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        margin-bottom: 18px;
        border-radius: 999px;
        background: rgba(57, 255, 120, .08);
        border: 1px solid rgba(57, 255, 120, .18);
        color: var(--accent);
        font-size: .82rem;
        font-weight: 600;
    }

    .static-hero h1 {
        font-size: clamp(2.2rem, 5vw, 3.4rem);
        margin-bottom: 12px;
    }

    .static-hero p {
        max-width: 680px;
        color: rgba(232, 240, 235, .6);
        line-height: 1.8;
    }

    .static-card {
        padding: 36px;
        border-radius: 28px;
        border: 1px solid rgba(255, 255, 255, .08);
        background: rgba(255, 255, 255, .03);
    }

    .static-card h2 {
        margin: 30px 0 10px;
        font-size: 1.2rem;
        font-weight: 750;
    }

    .static-card h2:first-child {
        margin-top: 0;
    }

    .static-card p,
    .static-card li {
        color: rgba(232, 240, 235, .74);
        font-size: .97rem;
        line-height: 1.85;
    }

    .static-card ul {
        margin: 10px 0 0 22px;
        list-style: disc;
    }

    .static-card ol {
        margin: 10px 0 0 22px;
        list-style: decimal;
    }

    .static-card li {
        margin-bottom: 6px;
        padding-left: 4px;
    }

    .static-updated {
        margin-top: 30px;
        color: rgba(232, 240, 235, .45);
        font-size: .85rem;
    }

    .static-contact {
        margin-top: 24px;
        padding: 24px 28px;
        border-radius: 22px;
        border: 1px solid rgba(57, 255, 120, .16);
        background: rgba(57, 255, 120, .05);
    }

    .static-contact strong {
        display: block;
        margin-bottom: 6px;
    }

    .static-contact p {
        color: rgba(232, 240, 235, .72);
        line-height: 1.7;
    }

    .static-contact a {
        color: var(--accent);
        font-weight: 700;
    }

    .static-links {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 24px;
    }

    .static-links a {
        padding: 10px 18px;
        border-radius: 999px;
        border: 1px solid rgba(255, 255, 255, .1);
        color: rgba(232, 240, 235, .75);
        font-size: .88rem;
        font-weight: 600;
        text-decoration: none;
    }

    .static-links a:hover,
    .static-links a.active {
        border-color: rgba(57, 255, 120, .35);
        color: var(--accent);
    }

    @media (max-width: 640px) {
        .static-wrap {
            padding: 112px 18px 72px;
        }

        .static-card {
            padding: 24px 20px;
            border-radius: 22px;
        }

        .static-contact {
            padding: 20px;
        }
    }
</style>
@endpush

@section('content')
@include('partials.navbar')

<main class="static-wrap">
    <header class="static-hero">
        <span class="static-kicker">@yield('page_kicker')</span>
        <h1>@yield('page_title')</h1>
        <p>@yield('page_intro')</p>
    </header>

    <article class="static-card">
        @yield('page_content')
    </article>

    <section class="static-contact">
        <strong>Ada pertanyaan?</strong>
        {{-- Ganti dengan email kontak Wandee yang sebenarnya --}}
        <p>Hubungi kami di <a href="mailto:admin@wandee.test">admin@wandee.test</a>.</p>
    </section>

    <nav class="static-links" aria-label="Halaman informasi">
        <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">Tentang Kami</a>
        <a href="{{ route('privacy') }}" class="{{ request()->routeIs('privacy') ? 'active' : '' }}">Kebijakan Privasi</a>
        <a href="{{ route('terms') }}" class="{{ request()->routeIs('terms') ? 'active' : '' }}">Syarat &amp; Ketentuan</a>
    </nav>
</main>

@include('partials.footer')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endsection