@extends('layouts.app')

@section('title', 'Profil - Wandee')
@section('body-class', 'profile-page')

@section('content')
@include('partials.navbar')

<section class="profile-wrap">

    <!-- HERO -->
    <div class="profile-hero">
        <span class="favorite-kicker">
            <i data-lucide="sparkles"></i>
            Profil Pengguna
        </span>
        <h1>Profil Saya</h1>
        <p>Kelola informasi akun dan personalisasi profile Wandee kamu di sini.</p>
    </div>

    <!-- PROFILE HEADER -->
    <div class="profile-avatar-wrap">
        <div class="profile-avatar">
            @if(!empty($user->photo))
                <img src="{{ asset('uploads/profile/' . $user->photo) }}" alt="Profil">
            @else
                <div class="profile-avatar-empty">
                    <i data-lucide="user"></i>
                </div>
            @endif

            <button
                type="button"
                class="profile-avatar-edit"
                onclick="document.getElementById('profilePhotoInput').click()"
                aria-label="Ubah foto profil">
                <i data-lucide="edit-2"></i>
            </button>
        </div>

        <div class="profile-heading">
            <div class="profile-heading-top">
                <div>
                    <h2>{{ $user->name }}</h2>
                    <p>{{ $user->email }}</p>
                </div>
                <span class="profile-role">
                    {{ ucfirst($user->role) }}
                </span>
            </div>

            <div class="profile-badges">
                <span>Wandee Member</span>
                <span>Bergabung sejak {{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->translatedFormat('d F Y') : '-' }}</span>
            </div>

            <div class="profile-summary-cards">
                <div class="profile-summary-card">
                    <strong>{{ $favorites_count }}</strong>
                    <span>Favorit</span>
                </div>

                <div class="profile-summary-card">
                    <strong>{{ $bookings_count }}</strong>
                    <span>Perjalanan</span>
                </div>

                <div class="profile-summary-card">
                    <strong>{{ $reviews_count }}</strong>
                    <span>Ulasan</span>
                </div>
            </div>
        </div>
    </div>

    <!-- PROFILE LAYOUT -->
    <div class="profile-layout">

        <!-- SIDEBAR -->
        <aside class="profile-sidebar">
            <div class="profile-menu">
                <a href="#" class="active">Informasi Akun</a>
                <a href="{{ route('user.favorite') }}">Favorit</a>
                <a href="{{ route('user.riwayat') }}">Riwayat Perjalanan</a>
                <a href="{{ route('user.ulasan') }}">Ulasan</a>
                <a href="javascript:void(0)" class="profile-menu-logout" data-logout-trigger>Keluar</a>
            </div>
        </aside>

        <!-- CONTENT -->
        <div class="profile-content">

            @if(session('success'))
                <div class="alert alert-success" style="background: rgba(57, 255, 120, 0.15); border: 1px solid rgba(57, 255, 120, 0.3); color: #39ff78; padding: 14px 20px; border-radius: var(--radius-sm); margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif

            @if(isset($errors) && $errors->any())
                <div class="alert alert-danger" style="background: rgba(255, 79, 129, 0.15); border: 1px solid rgba(255, 79, 129, 0.3); color: #ff4f81; padding: 14px 20px; border-radius: var(--radius-sm); margin-bottom: 20px;">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="profile-panel profile-details-panel">
                <div class="profile-panel-title">
                    <h3>Informasi Profil</h3>
                    <p>Lihat semua detail akun dan status profil.</p>
                </div>

                <div class="profile-info-grid">
                    <div class="profile-info-item">
                        <span>Nama Lengkap</span>
                        <strong>{{ $user->name }}</strong>
                    </div>

                    <div class="profile-info-item">
                        <span>Email</span>
                        <strong>{{ $user->email }}</strong>
                    </div>

                    <div class="profile-info-item">
                        <span>Peran</span>
                        <strong>{{ ucfirst($user->role) }}</strong>
                    </div>

                    <div class="profile-info-item">
                        <span>Bergabung Sejak</span>
                        <strong>{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->translatedFormat('d F Y') : '-' }}</strong>
                    </div>
                </div>
            </div>

            <div class="profile-panel">
                <div class="profile-panel-title">
                    <h3>Edit Profil</h3>
                    <p>Perbarui informasi akun kamu, termasuk foto profil.</p>
                </div>

                <form
                    class="profile-form"
                    action="{{ route('user.profile.update') }}"
                    method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="profile-field">
                        <label>Nama Lengkap</label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            required>
                    </div>

                    <div class="profile-field">
                        <label>Email</label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            required>
                    </div>

                    <div class="profile-field full-span file-upload-wrap">
                        <label>Foto Profil</label>
                        <label class="file-upload-box" for="profilePhotoInput">
                            <span class="file-upload-button">Choose File</span>
                            <span class="file-upload-name" id="profilePhotoName">No file chosen</span>
                        </label>
                        <input
                            type="file"
                            name="photo"
                            id="profilePhotoInput"
                            class="file-upload-input"
                            accept="image/jpeg,image/png,image/jpg">
                    </div>

                    <div class="profile-field full-span">
                        <label>Password Baru</label>
                        <input
                            type="password"
                            name="password"
                            placeholder="Kosongkan jika tidak ingin mengganti">
                    </div>

                    <div class="profile-save">
                        <button type="submit" class="btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>

        </div>

    </div>

</section>

@include('partials.logout_modal')
@include('partials.footer')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const profilePhotoInput = document.getElementById('profilePhotoInput');
        const profilePhotoName = document.getElementById('profilePhotoName');

        if (profilePhotoInput && profilePhotoName) {
            profilePhotoInput.addEventListener('change', () => {
                profilePhotoName.textContent = profilePhotoInput.files[0]?.name || 'No file chosen';
            });
        }

        const logoutTrigger = document.querySelector('[data-logout-trigger]');
        const logoutModal = document.getElementById('logoutModal');
        const logoutCancels = document.querySelectorAll('[data-logout-cancel]');

        if (logoutTrigger && logoutModal) {
            logoutTrigger.addEventListener('click', (e) => {
                e.preventDefault();
                logoutModal.classList.add('is-open');
                logoutModal.setAttribute('aria-hidden', 'false');
            });
        }

        logoutCancels.forEach(btn => {
            btn.addEventListener('click', () => {
                logoutModal.classList.remove('is-open');
                logoutModal.setAttribute('aria-hidden', 'true');
            });
        });

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endsection
