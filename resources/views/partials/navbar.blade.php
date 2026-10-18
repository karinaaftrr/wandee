<nav class="navbar">
    <a href="{{ url('/') }}" class="nav-logo">Wandee</a>

    <ul class="nav-links" id="mobileMenu">
        <li class="mobile-menu-header">
            <span class="mobile-menu-logo">Wandee</span>
            <button type="button" class="mobile-menu-close" id="mobileMenuClose" aria-label="Tutup Menu">
                <i data-lucide="x"></i>
            </button>
        </li>

        <li>
            <a href="{{ url('/') }}" class="{{ (request()->is('/') || request()->routeIs('user.dashboard')) && !request()->is('user/ulasan*') && !request()->is('user/riwayat*') && !request()->is('user/favorite*') && !request()->is('user/profile*') ? 'active' : '' }}">
                <i data-lucide="compass" class="mobile-nav-icon"></i>
                <span>Jelajah</span>
            </a>
        </li>

        <li>
            <a href="{{ url('/#destinations') }}">
                <i data-lucide="map-pin" class="mobile-nav-icon"></i>
                <span>Destinasi</span>
            </a>
        </li>

        <li>
            @auth
                <a href="{{ route('user.riwayat') }}" class="{{ request()->is('user/riwayat*') ? 'active' : '' }}">
                    <i data-lucide="calendar" class="mobile-nav-icon"></i>
                    <span>Perjalanan Saya</span>
                </a>
            @else
                <a href="{{ route('login') }}">
                    <i data-lucide="calendar" class="mobile-nav-icon"></i>
                    <span>Perjalanan Saya</span>
                </a>
            @endauth
        </li>

        <li>
            @auth
                <a href="{{ route('user.favorite') }}" class="{{ request()->is('user/favorite*') ? 'active' : '' }}">
                    <i data-lucide="heart" class="mobile-nav-icon"></i>
                    <span>Favorit</span>
                </a>
            @else
                <a href="{{ route('login') }}">
                    <i data-lucide="heart" class="mobile-nav-icon"></i>
                    <span>Favorit</span>
                </a>
            @endauth
        </li>

        <li>
            @auth
                <a href="{{ route('user.ulasan') }}" class="{{ request()->is('user/ulasan*') ? 'active' : '' }}">
                    <i data-lucide="message-square" class="mobile-nav-icon"></i>
                    <span>Ulasan</span>
                </a>
            @else
                <a href="{{ route('login') }}">
                    <i data-lucide="message-square" class="mobile-nav-icon"></i>
                    <span>Ulasan</span>
                </a>
            @endauth
        </li>

        <li>
            @auth
                <a href="{{ route('user.profile') }}" class="{{ request()->is('user/profile*') ? 'active' : '' }}">
                    <i data-lucide="user" class="mobile-nav-icon"></i>
                    <span>Profil</span>
                </a>
            @else
                <a href="{{ route('login') }}">
                    <i data-lucide="user" class="mobile-nav-icon"></i>
                    <span>Profil</span>
                </a>
            @endauth
        </li>

        <li class="mobile-menu-footer">
            @auth
                <div class="mobile-user-info">
                    <div class="mobile-user-avatar">
                        <i data-lucide="user"></i>
                    </div>
                    <div class="mobile-user-details">
                        <span class="mobile-user-name">{{ auth()->user()->name }}</span>
                        <span class="mobile-user-email">{{ auth()->user()->email }}</span>
                    </div>
                </div>

                <button
                    type="button"
                    class="mobile-logout-btn logout-button"
                    data-logout-link
                    style="margin-top: 10px; color: #ff5b68;"
                >
                    <i data-lucide="log-out"></i>
                    <span>Keluar</span>
                </button>
            @else
                <a href="{{ route('login') }}" class="btn-primary" style="width: 100%; text-align: center; justify-content: center; display: inline-flex;">
                    Masuk / Daftar
                </a>
            @endauth
        </li>
    </ul>

    <div class="nav-actions-group">
        <div class="nav-actions">
            @auth
                <a href="{{ route('user.profile') }}" class="btn-icon" aria-label="Profil">
                    <i data-lucide="user"></i>
                </a>

                <a href="{{ url('/#destinations') }}" class="btn-primary nav-btn-pesan">
                    Pesan Sekarang
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-icon" aria-label="Profil">
                    <i data-lucide="user"></i>
                </a>

                <a href="{{ route('login') }}" class="btn-primary nav-btn-pesan">
                    Pesan Sekarang
                </a>
            @endauth
        </div>

        <button type="button" class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Buka Menu" aria-expanded="false">
            <i data-lucide="menu"></i>
        </button>
    </div>
</nav>

<div class="nav-overlay" id="navOverlay" aria-hidden="true"></div>

@auth
    @include('partials.logout_modal')
@endauth
