<nav class="navbar">
    <a href="{{ url('/') }}" class="nav-logo">Wandee</a>

    <button class="mobile-menu-btn" id="mobileMenuBtn">☰</button>

    <ul class="nav-links" id="mobileMenu">
        <li>
            <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Jelajah</a>
        </li>
        <li>
            <a href="#destinations">Destinasi</a>
        </li>
    </ul>

    <div class="nav-actions">
        @auth
            <a href="{{ url('/') }}" class="btn-icon" aria-label="Profil">
                <i data-lucide="user"></i>
            </a>
            <a href="#destinations" class="btn-primary">Pesan Sekarang</a>
        @else
            <a href="{{ route('login') }}" class="btn-icon" aria-label="Profil">
                <i data-lucide="user"></i>
            </a>
            <a href="{{ route('login') }}" class="btn-primary">Masuk</a>
        @endauth
    </div>
</nav>
