<footer class="footer">
    <div class="container">
        <div class="footer-inner">
            <div class="footer-brand">
                <a href="{{ url('/') }}" class="footer-logo">Wandee</a>
                <p>Platform open trip modern untuk menemukan pengalaman perjalanan terbaik, teman traveling baru, dan destinasi impian.</p>
                <p class="footer-copy">&copy; {{ date('Y') }} Wandee. Semua hak dilindungi.</p>
            </div>

            <div class="footer-col">
                <h4>Jelajah</h4>
                <ul>
                    <li><a href="{{ url('/#destinations') }}">Destinasi</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Akun</h4>
                <ul>
                    <li><a href="{{ route('login') }}">Login / Daftar</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Info</h4>
                <ul>
                    <li><a href="{{ route('about') }}">Tentang Kami</a></li>
                    <li><a href="{{ route('privacy') }}">Kebijakan Privasi</a></li>
                    <li><a href="{{ route('terms') }}">Syarat &amp; Ketentuan</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>