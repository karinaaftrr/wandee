@extends('pages.layout')

@section('title', 'Kebijakan Privasi - Wandee')
@section('page_kicker', 'Privasi')
@section('page_title', 'Kebijakan Privasi')
@section('page_intro', 'Halaman ini menjelaskan data apa saja yang kami kumpulkan di Wandee, untuk apa dipakai, dan bagaimana kamu bisa mengelolanya.')

@section('page_content')
    <h2>Data yang kami kumpulkan</h2>
    <ul>
        <li><strong>Data akun:</strong> nama, alamat email, dan kata sandi. Kata sandi disimpan dalam bentuk terenkripsi (hash), bukan teks asli.</li>
        <li><strong>Foto profil:</strong> hanya kalau kamu mengunggahnya sendiri.</li>
        <li><strong>Data pemesanan:</strong> destinasi yang dipesan, jumlah peserta, dan status perjalanan.</li>
        <li><strong>Data pembayaran:</strong> metode dan jumlah pembayaran, serta bukti pembayaran yang kamu unggah.</li>
        <li><strong>Aktivitas di aplikasi:</strong> daftar favorit dan ulasan (rating, teks, dan foto) yang kamu tulis.</li>
    </ul>

    <h2>Untuk apa data dipakai</h2>
    <ul>
        <li>Membuat dan mengelola akunmu.</li>
        <li>Memproses pemesanan dan memverifikasi pembayaran.</li>
        <li>Menampilkan riwayat perjalanan, favorit, dan ulasanmu.</li>
        <li>Menjaga layanan tetap aman dan mencegah penyalahgunaan.</li>
    </ul>

    <h2>Siapa yang bisa melihat datamu</h2>
    <p>
        Admin Wandee dapat melihat data pemesanan dan bukti pembayaran untuk keperluan verifikasi. Ulasan yang kamu
        tulis, termasuk nama dan foto profilmu, tampil di halaman destinasi dan bisa dibaca pengguna lain. Kami tidak
        menjual datamu kepada pihak lain.
    </p>

    <h2>Penyimpanan dan keamanan</h2>
    <p>
        Data disimpan selama akunmu aktif atau selama dibutuhkan untuk menjalankan layanan. Kami berusaha menjaga
        keamanan data, tetapi tidak ada sistem yang sepenuhnya bebas risiko. Jaga kerahasiaan kata sandimu dan jangan
        membagikannya kepada siapa pun.
    </p>

    <h2>Hakmu atas data</h2>
    <ul>
        <li>Kamu bisa mengubah nama, email, foto, dan kata sandi kapan saja lewat halaman Profil.</li>
        <li>Kamu bisa meminta penghapusan akun dan data terkait dengan menghubungi kami lewat kontak di bawah.</li>
    </ul>

    <h2>Perubahan kebijakan</h2>
    <p>
        Kebijakan ini bisa diperbarui sewaktu-waktu. Tanggal pembaruan terakhir selalu tercantum di bagian bawah
        halaman ini.
    </p>

    <p class="static-updated">Terakhir diperbarui: 18 Oktober 2026</p>
@endsection