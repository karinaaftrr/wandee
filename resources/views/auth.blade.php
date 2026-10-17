@extends('layouts.app')

@section('title', 'Masuk dan Daftar - Wandee')
@section('body-class', 'auth-page')

@section('content')

@if (session('error') || (isset($errors) && $errors->any()))
    <div class="auth-error-popup is-open" id="authErrorPopup" role="alertdialog" aria-modal="true">
        <div class="auth-error-card">
            <h2>Terjadi Kesalahan</h2>
            <p>{{ session('error') ?? ($errors->first() ?? '') }}</p>
            <button class="btn-close-popup" id="closeAuthErrorPopup" type="button">Tutup</button>
        </div>
    </div>
@elseif (session('success'))
    <div class="auth-error-popup is-open" id="authErrorPopup" role="alertdialog" aria-modal="true" style="border-color: #10b981;">
        <div class="auth-error-card" style="border-top: 4px solid #10b981;">
            <h2 style="color: #10b981;">Berhasil</h2>
            <p>{{ session('success') }}</p>
            <button class="btn-close-popup" id="closeAuthErrorPopup" type="button" style="background: #10b981;">Tutup</button>
        </div>
    </div>
@endif

@include('partials.navbar')

<div class="auth-wrap">

    {{-- LOGIN PANEL --}}
    <div class="auth-card" id="loginPanel">
        <div class="auth-left">
            <div class="auth-left-text">
                <h2>Selamat datang kembali, petualangan baru menunggumu.</h2>
                <p>Masuk untuk melanjutkan eksplorasi destinasi impian bersama Wandee.</p>
            </div>
        </div>

        <div class="auth-right">

            @if (request()->has('expired'))
                <div class="session-expired-alert">
                    Session Anda telah berakhir. Silakan login kembali.
                </div>
            @endif

            <a href="{{ route('home') }}" class="auth-back">
                <i data-lucide="arrow-left"></i>
                Kembali
            </a>

            <h1>Masuk</h1>
            <p class="sub">Masukkan detail akun untuk masuk</p>

            <form action="{{ route('login.post') }}" method="POST">
                @csrf

                <div class="form-group">
                    <span class="form-icon">
                        <i data-lucide="user"></i>
                    </span>
                    <input
                        type="email"
                        class="form-input"
                        name="email"
                        placeholder="Email"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        required>
                </div>

                <div class="form-group">
                    <span class="form-icon">
                        <i data-lucide="lock-keyhole"></i>
                    </span>
                    <input
                        type="password"
                        class="form-input"
                        name="password"
                        placeholder="Password"
                        autocomplete="current-password"
                        required>
                </div>

                <p class="auth-forgot-link" style="text-align:right; margin-top:-5px; margin-bottom:15px; font-size:13px;">
                    <a href="#" id="showForgot" style="color:var(--accent, #38bdf8); text-decoration:none;">
                        Lupa Password?
                    </a>
                </p>

                <button class="btn-submit" type="submit">Masuk</button>
            </form>

            <p class="auth-footer-link">
                Tidak memiliki akun?
                <a href="#" id="showRegister">Daftar sekarang</a>
            </p>
        </div>
    </div>

    {{-- REGISTER PANEL --}}
    <div class="auth-card auth-card-hidden" id="registerPanel">
        <div class="auth-left">
            <div class="auth-left-text">
                <h2>Mulai perjalananmu bersama Wandee.</h2>
                <p>Buat akun baru dan temukan pengalaman traveling terbaik.</p>
            </div>
        </div>

        <div class="auth-right">
            <a href="#" class="auth-back" id="showLogin">
                <i data-lucide="arrow-left"></i>
                Kembali ke Masuk
            </a>

            <h1>Daftar</h1>
            <p class="sub">Buat akun baru sekarang</p>

            <form action="{{ route('register.post') }}" method="POST">
                @csrf

                <div class="form-group">
                    <input
                        type="text"
                        class="form-input"
                        name="name"
                        placeholder="Nama Lengkap"
                        value="{{ old('name') }}"
                        autocomplete="name"
                        required>
                </div>

                <div class="form-group">
                    <input
                        type="email"
                        class="form-input"
                        name="email"
                        placeholder="Email"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        required>
                </div>

                <div class="form-group">
                    <input
                        type="password"
                        class="form-input"
                        name="password"
                        placeholder="Password"
                        autocomplete="new-password"
                        minlength="6"
                        required>
                </div>

                <button class="btn-submit" type="submit">Daftar</button>
            </form>

            <p class="auth-footer-link">
                Sudah punya akun?
                <a href="#" id="showLoginText">Masuk sekarang</a>
            </p>
        </div>
    </div>

    {{-- FORGOT PASSWORD PANEL --}}
    <div class="auth-card auth-card-hidden" id="forgotPanel">
        <div class="auth-left">
            <div class="auth-left-text">
                <h2>Atur ulang sandi akun Anda.</h2>
                <p>Masukkan email terdaftar dan buat password baru Anda di Wandee.</p>
            </div>
        </div>

        <div class="auth-right">
            <a href="#" class="auth-back" id="showLoginFromForgot">
                <i data-lucide="arrow-left"></i>
                Kembali ke Masuk
            </a>

            <h1>Reset Password</h1>
            <p class="sub">Perbarui password akun Anda</p>

            <form action="{{ route('password.reset') }}" method="POST">
                @csrf

                <div class="form-group">
                    <span class="form-icon">
                        <i data-lucide="mail"></i>
                    </span>
                    <input
                        type="email"
                        class="form-input"
                        name="email"
                        placeholder="Email Terdaftar"
                        autocomplete="email"
                        required>
                </div>

                <div class="form-group">
                    <span class="form-icon">
                        <i data-lucide="lock-keyhole"></i>
                    </span>
                    <input
                        type="password"
                        class="form-input"
                        name="password"
                        placeholder="Password Baru"
                        autocomplete="new-password"
                        minlength="6"
                        required>
                </div>

                <div class="form-group">
                    <span class="form-icon">
                        <i data-lucide="lock-keyhole"></i>
                    </span>
                    <input
                        type="password"
                        class="form-input"
                        name="password_confirmation"
                        placeholder="Konfirmasi Password Baru"
                        autocomplete="new-password"
                        minlength="6"
                        required>
                </div>

                <button class="btn-submit" type="submit">Reset Password</button>
            </form>
        </div>
    </div>

</div>

@include('partials.footer')

@endsection
