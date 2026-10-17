<nav class="navbar">
    <a href="{{ url('/') }}" class="nav-logo">Wandee</a>

    <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Toggle Menu">
        ☰
    </button>

    <ul class="nav-links" id="mobileMenu">
        <li>
            <a href="{{ url('/') }}" class="{{ (request()->is('/') || request()->routeIs('user.dashboard')) && !request()->is('user/ulasan*') && !request()->is('user/riwayat*') && !request()->is('user/favorite*') && !request()->is('user/profile*') ? 'active' : '' }}">
                Jelajah
            </a>
        </li>

        <li>
            @auth
                <a href="{{ route('user.profile') }}" class="{{ request()->is('user/profile*') ? 'active' : '' }}">Profil</a>
            @else
                <a href="{{ route('login') }}">Profil</a>
            @endauth
        </li>

        <li>
            <a href="{{ url('/#destinations') }}">
                Destinasi
            </a>
        </li>

        <li>
            @auth
                <a href="{{ route('user.ulasan') }}" class="{{ request()->is('user/ulasan*') ? 'active' : '' }}">Ulasan</a>
            @else
                <a href="{{ route('login') }}">Ulasan</a>
            @endauth
        </li>

        <li>
            @auth
                <a href="{{ route('user.riwayat') }}" class="{{ request()->is('user/riwayat*') ? 'active' : '' }}">Perjalanan Saya</a>
            @else
                <a href="{{ route('login') }}">Perjalanan Saya</a>
            @endauth
        </li>

        <li>
            @auth
                <a href="{{ route('user.favorite') }}" class="{{ request()->is('user/favorite*') ? 'active' : '' }}">Favorit</a>
            @else
                <a href="{{ route('login') }}">Favorit</a>
            @endauth
        </li>
    </ul>

    <div class="nav-actions">
        @auth
            <a href="{{ route('user.profile') }}" class="btn-icon" aria-label="Profil">
                <i data-lucide="user"></i>
            </a>

            <a href="{{ url('/#destinations') }}" class="btn-primary">
                Pesan Sekarang
            </a>
        @else
            <a href="{{ route('login') }}" class="btn-icon" aria-label="Profil">
                <i data-lucide="user"></i>
            </a>

            <a href="{{ route('login') }}" class="btn-primary">
                Pesan Sekarang
            </a>
        @endauth
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const navLinks = document.querySelectorAll(".nav-links a");

            function checkActiveHash() {
                const hash = window.location.hash;
                const pathname = window.location.pathname;
                const isHome = pathname === "/" || pathname === "" || pathname.includes("/user/dashboard");

                if (isHome) {
                    navLinks.forEach(link => {
                        const href = link.getAttribute("href");
                        if (hash === "#destinations") {
                            if (href && href.includes("#destinations")) {
                                link.classList.add("active");
                            } else {
                                link.classList.remove("active");
                            }
                        } else {
                            if (href && href.includes("#destinations")) {
                                link.classList.remove("active");
                            } else if (href === "{{ url('/') }}" || (href && href.endsWith("/user/dashboard"))) {
                                if (link.textContent.trim() === "Jelajah") {
                                    link.classList.add("active");
                                }
                            }
                        }
                    });
                }
            }

            checkActiveHash();
            window.addEventListener("hashchange", checkActiveHash);

            navLinks.forEach(link => {
                link.addEventListener("click", function () {
                    const href = this.getAttribute("href");
                    if (href && href.includes("#destinations")) {
                        navLinks.forEach(l => l.classList.remove("active"));
                        this.classList.add("active");
                    }
                });
            });

            const menuBtn = document.getElementById("mobileMenuBtn");
            const mobileMenu = document.getElementById("mobileMenu");

            if (menuBtn && mobileMenu) {
                if (localStorage.getItem("mobileSidebar") === "open") {
                    mobileMenu.classList.add("show");
                }

                menuBtn.addEventListener("click", function () {
                    mobileMenu.classList.toggle("show");
                    localStorage.setItem("mobileSidebar", mobileMenu.classList.contains("show") ? "open" : "closed");
                });
            }

            if (typeof lucide !== "undefined") {
                lucide.createIcons();
            }
        });
    </script>
</nav>
