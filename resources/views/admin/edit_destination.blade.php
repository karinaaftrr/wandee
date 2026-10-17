@extends('layouts.admin')

@section('title', 'Edit Destinasi - Wandee')

@section('content')

    <div class="admin-header-small">
        <div>
            <span class="admin-badge">Edit Destinasi</span>
            <h1>{{ $data->title }}</h1>
            <p>Perbarui informasi destinasi wisata.</p>
        </div>
        <a href="{{ route('admin.manage') }}" class="btn-ghost" style="padding:10px 20px;font-size:.85rem;">
            <i data-lucide="arrow-left" style="margin-right:6px;"></i> Kembali
        </a>
    </div>

    @if ($errors->any())
        <div style="background:rgba(255,79,129,.12);border:1px solid rgba(255,79,129,.25);border-radius:14px;padding:14px 20px;margin-bottom:24px;color:#ff4f81;font-size:.9rem;">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="chart-card chart-card-custom" style="padding:32px;">
        <form action="{{ route('admin.update_destination', $data->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
                <div class="form-group" style="margin-bottom:0;">
                    <label style="display:block;font-size:.82rem;color:var(--text-muted);margin-bottom:6px;">Judul</label>
                    <input type="text" class="form-input" name="title" value="{{ old('title', $data->title) }}" required>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label style="display:block;font-size:.82rem;color:var(--text-muted);margin-bottom:6px;">Lokasi</label>
                    <input type="text" class="form-input" name="location" value="{{ old('location', $data->location) }}" required>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:20px;margin-bottom:20px;">
                <div class="form-group" style="margin-bottom:0;">
                    <label style="display:block;font-size:.82rem;color:var(--text-muted);margin-bottom:6px;">Kategori</label>
                    <select class="form-input" name="category" required>
                        <option value="Gunung" {{ $data->category === 'Gunung' ? 'selected' : '' }}>Gunung</option>
                        <option value="Pantai" {{ $data->category === 'Pantai' ? 'selected' : '' }}>Pantai</option>
                        <option value="Air Terjun" {{ $data->category === 'Air Terjun' ? 'selected' : '' }}>Air Terjun</option>
                        <option value="Kota" {{ $data->category === 'Kota' ? 'selected' : '' }}>Kota</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label style="display:block;font-size:.82rem;color:var(--text-muted);margin-bottom:6px;">Harga</label>
                    <input type="text" class="form-input" name="price" value="{{ old('price', $data->price) }}" required>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label style="display:block;font-size:.82rem;color:var(--text-muted);margin-bottom:6px;">Kuota</label>
                    <input type="number" class="form-input" name="quota" value="{{ old('quota', $data->quota) }}" min="0" required>
                </div>
            </div>

            <div class="form-group" style="margin-bottom:20px;">
                <label style="display:block;font-size:.82rem;color:var(--text-muted);margin-bottom:6px;">Tanggal Trip</label>
                <input type="text" class="form-input" name="trip_date" value="{{ old('trip_date', $data->trip_date) }}" placeholder="cth: 15 Jan 2026 - 18 Jan 2026">
            </div>

            <div class="form-group" style="margin-bottom:20px;">
                <label style="display:block;font-size:.82rem;color:var(--text-muted);margin-bottom:6px;">Deskripsi</label>
                <textarea class="form-input" name="description" rows="4" style="resize:vertical;">{{ old('description', $data->description) }}</textarea>
            </div>

            <div class="form-group" style="margin-bottom:20px;">
                <label style="display:block;font-size:.82rem;color:var(--text-muted);margin-bottom:6px;">Itinerary</label>
                <textarea class="form-input" name="itinerary" rows="3" style="resize:vertical;">{{ old('itinerary', $data->itinerary) }}</textarea>
            </div>

            <div class="form-group" style="margin-bottom:24px;">
                <label style="display:block;font-size:.82rem;color:var(--text-muted);margin-bottom:6px;">Fasilitas</label>
                <textarea class="form-input" name="facilities" rows="3" style="resize:vertical;">{{ old('facilities', $data->facilities) }}</textarea>
            </div>

            <button type="submit" class="btn-primary" style="padding:12px 32px;">Simpan Perubahan</button>
        </form>
    </div>

@endsection
