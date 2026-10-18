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

    <form class="manage-form" action="{{ route('admin.update_destination', $data->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="manage-grid">
            <div class="manage-field">
                <label for="title">Nama Destinasi</label>
                <input type="text" id="title" name="title" value="{{ old('title', $data->title) }}" required>
            </div>

            <div class="manage-field">
                <label for="location">Lokasi</label>
                <input type="text" id="location" name="location" value="{{ old('location', $data->location) }}" required>
            </div>

            <div class="manage-field">
                <label for="category">Kategori</label>
                <select id="category" name="category" required>
                    @foreach (['Gunung', 'Pantai', 'Air Terjun', 'Kota'] as $cat)
                        <option value="{{ $cat }}" {{ old('category', $data->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <div class="manage-field">
                <label for="price">Harga</label>
                <input type="number" id="price" name="price" value="{{ old('price', (int) $data->price) }}" min="0" step="1" required>
                <small style="color:rgba(232,240,235,.5);">Masukkan angka. Contoh: 1500000 atau 1500000.00</small>
            </div>

            <div class="manage-field">
                <label for="trip_start">Tanggal Mulai Trip</label>
                <input type="date" id="trip_start" name="trip_start" value="{{ old('trip_start', $tripDates['start']) }}"
                       onchange="document.getElementById('trip_end').min = this.value;">
                @if ($data->trip_date)
                    <small style="color:rgba(232,240,235,.5);">
                        Tersimpan saat ini: {{ $data->trip_date }}@if (!$tripDates['start']) (tidak terbaca otomatis, pilih ulang tanggalnya)@endif
                    </small>
                @endif
            </div>

            <div class="manage-field">
                <label for="trip_end">Tanggal Selesai Trip</label>
                <input type="date" id="trip_end" name="trip_end" value="{{ old('trip_end', $tripDates['end']) }}" min="{{ old('trip_start', $tripDates['start']) }}">
                <small style="color:rgba(232,240,235,.5);">Kosongkan jika trip hanya satu hari.</small>
            </div>

            <div class="manage-field">
                <label for="quota">Kuota Peserta</label>
                <input type="number" id="quota" name="quota" value="{{ old('quota', $data->quota) }}" min="0" required>
            </div>

            <div class="manage-field">
                <label for="facilities">Fasilitas (Pisahkan dengan koma)</label>
                <input type="text" id="facilities" name="facilities" value="{{ old('facilities', $data->facilities) }}" placeholder="Contoh: Transportasi, Porter, Tenda, Makan">
                <small style="color:rgba(232,240,235,.5);">Gunakan koma sebagai pemisah.</small>
            </div>

            <div class="manage-field">
                <label for="rating">Rating Akumulasi</label>
                <input type="text" id="rating" name="rating" value="{{ old('rating', number_format((float) $data->rating, 1, ',', '')) }}" inputmode="decimal" placeholder="Contoh: 4,5">
            </div>

            <div class="manage-field full-span">
                <label for="description">Deskripsi</label>
                <textarea id="description" name="description" rows="5">{{ old('description', $data->description) }}</textarea>
            </div>

            @include('partials.itinerary_builder', ['itinerary' => $itinerary])

            <div class="manage-field full-span">
                <label>Ganti Gambar (Opsional)</label>
                <div style="display:flex;align-items:center;gap:16px;margin-bottom:6px;">
                    <img src="{{ asset('assets/img/' . $data->image) }}" alt="{{ $data->title }}"
                         style="width:90px;height:90px;object-fit:cover;border-radius:12px;"
                         onerror="this.onerror=null;this.src='{{ asset('assets/img/gunung.png') }}';">
                    <span style="color:rgba(232,240,235,.5);font-size:.85rem;">Gambar saat ini</span>
                </div>
                <label class="file-upload-box" for="destImage">
                    <span class="file-upload-button">Choose File</span>
                    <span class="file-upload-name" id="destImageName">Belum ada file dipilih</span>
                </label>
                <input type="file" id="destImage" name="image" class="file-upload-input" accept="image/jpeg,image/png">
                <small style="color:rgba(232,240,235,.5);">Format JPG / JPEG / PNG, maksimal 5 MB. Kosongkan jika tidak ingin mengganti gambar.</small>
            </div>
        </div>

        <button type="submit" class="btn-primary">Update Destinasi</button>
    </form>

@endsection