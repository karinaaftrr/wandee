@extends('layouts.admin')

@section('title', 'Manage Destinasi - Wandee')

@section('content')

    <div class="admin-header-small">
        <div>
            <span class="admin-badge">Kelola Destinasi</span>
            <h1>Manage Destinasi</h1>
            <p>Lihat, edit, dan hapus destinasi wisata yang tersedia di Wandee.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="admin-alert-success" style="background:rgba(57,255,120,.12);border:1px solid rgba(57,255,120,.25);border-radius:14px;padding:14px 20px;margin-bottom:24px;color:var(--accent);font-size:.9rem;">
            {{ session('success') }}
        </div>
    @endif

    <div class="dashboard-table-card dashboard-table-card-custom">
        <div class="dashboard-table-wrap">
            <table class="dashboard-table-custom">
                <thead>
                    <tr class="dashboard-table-tr-header">
                        <th class="dashboard-table-th">ID</th>
                        <th class="dashboard-table-th">Gambar</th>
                        <th class="dashboard-table-th">Judul</th>
                        <th class="dashboard-table-th">Lokasi</th>
                        <th class="dashboard-table-th">Kategori</th>
                        <th class="dashboard-table-th">Harga</th>
                        <th class="dashboard-table-th">Kuota</th>
                        <th class="dashboard-table-th">Rating</th>
                        <th class="dashboard-table-th">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($destinations as $dest)
                        <tr class="dashboard-table-tr">
                            <td class="dashboard-table-td">#{{ $dest->id }}</td>
                            <td class="dashboard-table-td">
                                <img src="{{ asset('assets/img/' . $dest->image) }}" alt="{{ $dest->title }}"
                                     style="width:60px;height:40px;object-fit:cover;border-radius:8px;"
                                     onerror="this.onerror=null;this.src='{{ asset('assets/img/gunung.png') }}';">
                            </td>
                            <td class="dashboard-table-td"><strong>{{ $dest->title }}</strong></td>
                            <td class="dashboard-table-td-muted">{{ $dest->location }}</td>
                            <td class="dashboard-table-td-muted">{{ $dest->category }}</td>
                            <td class="dashboard-table-td-accent">Rp {{ number_format((float)$dest->price, 0, ',', '.') }}</td>
                            <td class="dashboard-table-td-muted">{{ $dest->quota }}</td>
                            <td class="dashboard-table-td">★ {{ number_format((float)($dest->rating ?? 0), 1) }}</td>
                            <td class="dashboard-table-td">
                                <div style="display:flex;gap:8px;">
                                    <a href="{{ route('admin.edit_destination', $dest->id) }}"
                                       class="btn-primary" style="padding:8px 16px;font-size:.78rem;">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.delete_destination', $dest->id) }}" method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus destinasi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-ghost" style="padding:8px 16px;font-size:.78rem;border-color:rgba(255,79,129,.4);color:#ff4f81;">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="dashboard-table-empty">Belum ada destinasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
