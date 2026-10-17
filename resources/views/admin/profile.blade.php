@extends('layouts.admin')

@section('title', 'Profil Admin - Wandee')

@section('content')

    <div class="admin-header-small">
        <div>
            <span class="admin-badge">Profil Saya</span>
            <h1>Profil Admin</h1>
            <p>Informasi akun admin Anda.</p>
        </div>
    </div>

    <div class="chart-card chart-card-custom" style="padding:40px;max-width:600px;">
        <div style="display:flex;align-items:center;gap:24px;margin-bottom:32px;">
            @if ($user->photo)
                <img src="{{ asset('uploads/profile/' . $user->photo) }}" alt="Foto"
                     style="width:80px;height:80px;border-radius:50%;object-fit:cover;">
            @else
                <div style="width:80px;height:80px;border-radius:50%;background:rgba(57,255,120,.12);display:grid;place-items:center;">
                    <i data-lucide="user" style="width:36px;height:36px;color:var(--accent);"></i>
                </div>
            @endif
            <div>
                <h2 style="font-size:1.3rem;margin-bottom:4px;">{{ $user->name }}</h2>
                <p style="color:var(--text-muted);font-size:.9rem;">{{ $user->email }}</p>
            </div>
        </div>

        <div style="display:grid;gap:18px;">
            <div style="padding:16px 20px;border-radius:14px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.06);">
                <p style="font-size:.75rem;text-transform:uppercase;letter-spacing:.1em;color:var(--text-muted);margin-bottom:4px;">Nama</p>
                <p style="font-size:1rem;">{{ $user->name }}</p>
            </div>
            <div style="padding:16px 20px;border-radius:14px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.06);">
                <p style="font-size:.75rem;text-transform:uppercase;letter-spacing:.1em;color:var(--text-muted);margin-bottom:4px;">Email</p>
                <p style="font-size:1rem;">{{ $user->email }}</p>
            </div>
            <div style="padding:16px 20px;border-radius:14px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.06);">
                <p style="font-size:.75rem;text-transform:uppercase;letter-spacing:.1em;color:var(--text-muted);margin-bottom:4px;">Role</p>
                <p style="font-size:1rem;color:var(--accent);font-weight:600;">{{ ucfirst($user->role) }}</p>
            </div>
            <div style="padding:16px 20px;border-radius:14px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.06);">
                <p style="font-size:.75rem;text-transform:uppercase;letter-spacing:.1em;color:var(--text-muted);margin-bottom:4px;">Bergabung Sejak</p>
                <p style="font-size:1rem;">{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d F Y') : '-' }}</p>
            </div>
        </div>
    </div>

@endsection
