@php
    $itineraryRows = old('itinerary', $itinerary ?? []);
@endphp

<div class="manage-field full-span">
    <label>Rencana Perjalanan (Itinerary Per Hari)</label>

    <div class="itinerary-builder" id="itineraryBuilder">
        @foreach ($itineraryRows as $i => $row)
            <div class="itinerary-day-card">
                <div class="itinerary-day-header">
                    <span class="itinerary-day-title">Hari ke-{{ $loop->iteration }}</span>
                    <button type="button" class="btn-remove-day" data-remove-day>
                        <i data-lucide="trash-2"></i> Hapus
                    </button>
                </div>
                <div class="itinerary-fields">
                    <div>
                        <label>Judul Kegiatan</label>
                        <input type="text" name="itinerary[{{ $i }}][title]" value="{{ $row['title'] ?? '' }}" placeholder="Contoh: Perjalanan &amp; Aklimatisasi">
                    </div>
                    <div>
                        <label>Waktu</label>
                        <input type="text" name="itinerary[{{ $i }}][time]" value="{{ $row['time'] ?? '' }}" placeholder="Contoh: 10.00 - 18.00 WIB">
                    </div>
                    <div class="full-width">
                        <label>Deskripsi Kegiatan</label>
                        <textarea name="itinerary[{{ $i }}][desc]" rows="3" placeholder="Detail kegiatan hari ini...">{{ $row['desc'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div>
        <button type="button" class="btn-secondary" id="addItineraryDay">
            <i data-lucide="plus-circle"></i> Tambah Hari Itinerary
        </button>
    </div>
</div>

<template id="itineraryTemplate">
    <div class="itinerary-day-card">
        <div class="itinerary-day-header">
            <span class="itinerary-day-title">Hari ke-__N__</span>
            <button type="button" class="btn-remove-day" data-remove-day>
                <i data-lucide="trash-2"></i> Hapus
            </button>
        </div>
        <div class="itinerary-fields">
            <div>
                <label>Judul Kegiatan</label>
                <input type="text" name="itinerary[__I__][title]" placeholder="Contoh: Perjalanan &amp; Aklimatisasi">
            </div>
            <div>
                <label>Waktu</label>
                <input type="text" name="itinerary[__I__][time]" placeholder="Contoh: 10.00 - 18.00 WIB">
            </div>
            <div class="full-width">
                <label>Deskripsi Kegiatan</label>
                <textarea name="itinerary[__I__][desc]" rows="3" placeholder="Detail kegiatan hari ini..."></textarea>
            </div>
        </div>
    </div>
</template>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const builder  = document.getElementById('itineraryBuilder');
    const template = document.getElementById('itineraryTemplate');
    const addBtn   = document.getElementById('addItineraryDay');
    let counter    = builder.querySelectorAll('.itinerary-day-card').length;

    function renumber() {
        builder.querySelectorAll('.itinerary-day-card').forEach(function (card, idx) {
            card.querySelector('.itinerary-day-title').textContent = 'Hari ke-' + (idx + 1);
        });
    }

    addBtn.addEventListener('click', function () {
        const html = template.innerHTML
            .replace(/__I__/g, counter++)
            .replace(/__N__/g, builder.children.length + 1);
        builder.insertAdjacentHTML('beforeend', html);
        if (window.lucide) lucide.createIcons();
    });

    builder.addEventListener('click', function (e) {
        const btn = e.target.closest('[data-remove-day]');
        if (!btn) return;
        btn.closest('.itinerary-day-card').remove();
        renumber();
    });

    // Nama file pada kotak upload gambar
    const fileInput = document.getElementById('destImage');
    const fileName  = document.getElementById('destImageName');
    if (fileInput && fileName) {
        fileInput.addEventListener('change', function () {
            fileName.textContent = fileInput.files.length ? fileInput.files[0].name : 'Belum ada file dipilih';
        });
    }
});
</script>
@endpush
