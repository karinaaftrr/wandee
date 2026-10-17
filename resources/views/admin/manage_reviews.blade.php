@extends('layouts.admin')

@section('title', 'Manage Ulasan - Wandee')

@section('content')

    <div class="admin-header-small">
        <div>
            <span class="admin-badge">Kelola Ulasan</span>
            <h1>Manage Ulasan</h1>
            <p>Lihat dan kelola ulasan dari pengguna terhadap destinasi.</p>
        </div>
    </div>

    @if (session('success'))
        <div style="background:rgba(57,255,120,.12);border:1px solid rgba(57,255,120,.25);border-radius:14px;padding:14px 20px;margin-bottom:24px;color:var(--accent);font-size:.9rem;">
            {{ session('success') }}
        </div>
    @endif

    <div class="dashboard-table-card dashboard-table-card-custom">
        <div class="dashboard-table-wrap">
            <table class="dashboard-table-custom">
                <thead>
                    <tr class="dashboard-table-tr-header">
                        <th class="dashboard-table-th">ID</th>
                        <th class="dashboard-table-th">Pengguna</th>
                        <th class="dashboard-table-th">Destinasi</th>
                        <th class="dashboard-table-th">Rating</th>
                        <th class="dashboard-table-th">Ulasan</th>
                        <th class="dashboard-table-th">Tanggal</th>
                        <th class="dashboard-table-th">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reviews as $review)
                        <tr class="dashboard-table-tr">
                            <td class="dashboard-table-td">#{{ $review->id }}</td>
                            <td class="dashboard-table-td"><strong>{{ $review->user_name ?? 'Anonim' }}</strong></td>
                            <td class="dashboard-table-td-muted">{{ $review->destination_title ?? '-' }}</td>
                            <td class="dashboard-table-td-accent">★ {{ $review->rating }}.0</td>
                            <td class="dashboard-table-td-muted" style="max-width:250px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                {{ $review->review ?? '-' }}
                            </td>
                            <td class="dashboard-table-td-muted">
                                {{ $review->created_at ? \Carbon\Carbon::parse($review->created_at)->format('d/m/Y') : '-' }}
                            </td>
                            <td class="dashboard-table-td">
                                <form action="{{ route('admin.review_delete', $review->id) }}" method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus ulasan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-ghost" style="padding:6px 14px;font-size:.75rem;border-color:rgba(255,79,129,.4);color:#ff4f81;">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="dashboard-table-empty">Belum ada ulasan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
