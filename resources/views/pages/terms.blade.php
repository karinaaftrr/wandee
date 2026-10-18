@extends('pages.layout')

@section('title', 'Syarat & Ketentuan - Wandee')
@section('page_kicker', 'Ketentuan')
@section('page_title', 'Syarat & Ketentuan')
@section('page_intro', 'Dengan memakai Wandee, kamu setuju dengan ketentuan berikut. Mohon dibaca sebelum memesan trip.')

@section('page_content')
    <h2>1. Akun</h2>
    <ul>
        <li>Data yang kamu isi saat mendaftar harus benar dan masih berlaku.</li>
        <li>Kamu bertanggung jawab menjaga kerahasiaan kata sandi dan semua aktivitas di akunmu.</li>
    </ul>

    <h2>2. Pemesanan dan pembayaran</h2>
    <ul>
        <li>Pemesanan dianggap sah setelah pembayaran diverifikasi oleh admin.</li>
        <li>Pembayaran dilakukan dengan mengunggah bukti pembayaran. Status pembayaran bisa berupa menunggu verifikasi, terverifikasi, atau ditolak.</li>
        <li>Kalau pembayaran ditolak, kamu bisa mengunggah ulang bukti yang valid dari halaman status pembayaran.</li>
        <li>Harga, jadwal, dan kuota mengikuti informasi yang tampil di halaman destinasi saat kamu memesan.</li>
    </ul>

    <h2>3. Jadwal dan perubahan trip</h2>
    <p>
        Kuota setiap trip terbatas. Jadwal atau rencana perjalanan dapat berubah karena cuaca, kondisi lapangan, atau
        keadaan lain di luar kendali. Kalau ada perubahan, kami akan menyampaikannya lewat admin Wandee.
    </p>

    <h2>4. Pembatalan dan pengembalian dana</h2>
    <p>
        Permintaan pembatalan dan pengembalian dana dikonfirmasi melalui admin Wandee dan mengikuti ketentuan
        masing-masing trip. Hubungi kami lewat kontak di bawah sebelum jadwal keberangkatan.
    </p>

    <h2>5. Selama perjalanan</h2>
    <ul>
        <li>Ikuti arahan pemandu dan aturan di lokasi wisata.</li>
        <li>Pastikan kondisi fisikmu sesuai dengan tingkat kesulitan trip yang dipilih.</li>
        <li>Jaga kebersihan dan kelestarian lingkungan di sepanjang perjalanan.</li>
    </ul>

    <h2>6. Ulasan</h2>
    <ul>
        <li>Ulasan hanya bisa ditulis setelah trip selesai dan pembayaranmu terverifikasi.</li>
        <li>Satu akun menulis satu ulasan untuk setiap destinasi.</li>
        <li>Tulis berdasarkan pengalamanmu sendiri. Ulasan tidak boleh berisi ujaran kebencian, SARA, spam, atau informasi pribadi orang lain.</li>
        <li>Admin berhak menghapus ulasan yang melanggar ketentuan ini.</li>
    </ul>

    <h2>7. Perubahan ketentuan</h2>
    <p>
        Ketentuan ini dapat diperbarui sewaktu-waktu. Kalau kamu terus memakai Wandee setelah ada perubahan, kami
        anggap kamu menyetujui versi yang terbaru.
    </p>

    <p class="static-updated">Terakhir diperbarui: 18 Oktober 2026</p>
@endsection