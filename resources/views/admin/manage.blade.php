@extends('layouts.admin')

@section('title', 'Manage Destinasi - Wandee')

@section('content')

    <div class="admin-header-small">
        <div>
            <span class="admin-badge">Kelola Destinasi</span>
            <h1>Manage Destinasi</h1>
            <p>Kelola seluruh destinasi wisata Wandee dari dashboard admin.</p>
        </div>

        <div class="admin-user">
            @if (auth()->user()->photo)
                <img src="{{ asset('uploads/profile/' . auth()->user()->photo) }}" alt="Foto admin">
            @else
                <div class="admin-user-empty"><i data-lucide="user"></i></div>
            @endif
            <div>
                <strong>{{ auth()->user()->name }}</strong>
                <p>{{ auth()->user()->email }}</p>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div style="background:rgba(57,255,120,.12);border:1px solid rgba(57,255,120,.25);border-radius:14px;padding:14px 20px;margin-bottom:24px;color:var(--accent);font-size:.9rem;">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div style="background:rgba(255,79,129,.12);border:1px solid rgba(255,79,129,.25);border-radius:14px;padding:14px 20px;margin-bottom:24px;color:#ff4f81;font-size:.9rem;">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- FORM TAMBAH DESTINASI --}}
    <form class="manage-form" action="{{ route('admin.store_destination') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="manage-grid">
            <div class="manage-field">
                <label for="title">Nama Destinasi</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required>
            </div>

            <div class="manage-field">
                <label for="location">Lokasi</label>
                <input type="text" id="location" name="location" value="{{ old('location') }}" required>
            </div>

            <div class="manage-field">
                <label for="category">Kategori</label>
                <select id="category" name="category" required>
                    <option value="" disabled {{ old('category') ? '' : 'selected' }}>Pilih Kategori</option>
                    @foreach (['Gunung', 'Pantai', 'Air Terjun', 'Kota'] as $cat)
                        <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <div class="manage-field">
                <label for="price">Harga</label>
                <input type="number" id="price" name="price" value="{{ old('price') }}" min="0" step="1" required>
                <small style="color:rgba(232,240,235,.5);">Masukkan angka. Contoh: 1500000 atau 1500000.00</small>
            </div>

            <div class="manage-field">
                <label for="trip_start">Tanggal Mulai Trip</label>
                <input type="date" id="trip_start" name="trip_start" value="{{ old('trip_start') }}"
                       onchange="document.getElementById('trip_end').min = this.value;">
            </div>

            <div class="manage-field">
                <label for="trip_end">Tanggal Selesai Trip</label>
                <input type="date" id="trip_end" name="trip_end" value="{{ old('trip_end') }}" min="{{ old('trip_start') }}">
                <small style="color:rgba(232,240,235,.5);">Kosongkan jika trip hanya satu hari.</small>
            </div>

            <div class="manage-field">
                <label for="quota">Kuota Peserta</label>
                <input type="number" id="quota" name="quota" value="{{ old('quota') }}" min="0" required>
            </div>

            <div class="manage-field full-span">
                <label for="facilities">Fasilitas (Pisahkan dengan koma)</label>
                <input type="text" id="facilities" name="facilities" value="{{ old('facilities') }}" placeholder="Contoh: Transportasi, Porter, Tenda, Makan">
            </div>

            <div class="manage-field full-span">
                <label for="description">Deskripsi</label>
                <textarea id="description" name="description" rows="5">{{ old('description') }}</textarea>
            </div>

            @include('partials.itinerary_builder', ['itinerary' => []])

            <div class="manage-field full-span">
                <label>Upload Gambar</label>
                <label class="file-upload-box" for="destImage">
                    <span class="file-upload-button">Choose File</span>
                    <span class="file-upload-name" id="destImageName">Belum ada file dipilih</span>
                </label>
                <input type="file" id="destImage" name="image" class="file-upload-input" accept="image/jpeg,image/png" required>
                <small style="color:rgba(232,240,235,.5);">Format JPG / JPEG / PNG, maksimal 5 MB.</small>
            </div>
        </div>

        <button type="submit" class="btn-primary">Tambah Destinasi</button>
    </form>

    {{-- TABEL DESTINASI --}}
    <div class="dashboard-table-card dashboard-table-card-custom">
        <div class="dashboard-table-wrap">
            <table class="dashboard-table-custom">
                <thead>
                    <tr class="dashboard-table-tr-header">
                        <th class="dashboard-table-th">Gambar</th>
                        <th class="dashboard-table-th">Nama</th>
                        <th class="dashboard-table-th">Kategori</th>
                        <th class="dashboard-table-th">Lokasi</th>
                        <th class="dashboard-table-th">Harga</th>
                        <th class="dashboard-table-th">Tanggal</th>
                        <th class="dashboard-table-th">Kuota</th>
                        <th class="dashboard-table-th">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($destinations as $dest)
                        <tr class="dashboard-table-tr">
                            <td class="dashboard-table-td">
                                <img src="{{ asset('assets/img/' . $dest->image) }}" alt="{{ $dest->title }}"
                                     style="width:60px;height:60px;object-fit:cover;border-radius:10px;"
                                     onerror="this.onerror=null;this.src='{{ asset('assets/img/gunung.png') }}';">
                            </td>
                            <td class="dashboard-table-td"><strong>{{ $dest->title }}</strong></td>
                            <td class="dashboard-table-td-muted">{{ $dest->category }}</td>
                            <td class="dashboard-table-td-muted">{{ $dest->location }}</td>
                            <td class="dashboard-table-td-accent">Rp {{ number_format((float) $dest->price, 0, ',', '.') }}</td>
                            <td class="dashboard-table-td-muted">{{ $dest->trip_date ?: '-' }}</td>
                            <td class="dashboard-table-td-muted">{{ $dest->quota }} peserta</td>
                            <td class="dashboard-table-td">
                                <div style="display:flex;gap:8px;">
                                    <a href="{{ route('admin.edit_destination', $dest->id) }}"
                                       style="padding:8px 18px;font-size:.8rem;font-weight:700;border-radius:10px;background:#5b4be8;color:#fff;text-decoration:none;">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.delete_destination', $dest->id) }}" method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus destinasi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                style="padding:8px 18px;font-size:.8rem;font-weight:700;border-radius:10px;background:#ef4444;color:#fff;border:none;cursor:pointer;">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="dashboard-table-empty">Belum ada destinasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection