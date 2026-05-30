<!-- resources/views/alternatif/form.blade.php -->

@php
    // Default values untuk create
    $isEdit = isset($mode) && $mode === 'edit';
    $a = $isEdit ? $alternatif : null;
@endphp

<div class="row">
    {{-- Kode Alternatif (khusus tambah) --}}
    @unless ($isEdit)
        <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold text-primary">Kode Alternatif <span class="text-danger">*</span></label>
            <input type="text" name="kode_alternatif" class="form-control" value="" placeholder="Contoh: A1" required>
        </div>
    @endunless

    {{-- Nama Wisata --}}
    <div class="col-md-{{ $isEdit ? '12' : '8' }} mb-3">
        <label class="form-label fw-semibold text-primary">Nama Wisata <span class="text-danger">*</span></label>
        <input type="text" name="nama_wisata" class="form-control" value="{{ $a->nama_wisata ?? '' }}" required>
    </div>
</div>

<div class="row">
    {{-- Jenis Wisata --}}
    <div class="col-md-4 mb-3">
        <label class="form-label fw-semibold text-primary">Jenis Wisata <span class="text-danger">*</span></label>
        <select name="jenis_wisata" class="form-select" required>
            <option value="">-- Pilih Jenis --</option>
            <option value="alam" {{ ($a->jenis_wisata ?? '') === 'alam' ? 'selected' : '' }}>Wisata Alam</option>
            <option value="buatan" {{ ($a->jenis_wisata ?? '') === 'buatan' ? 'selected' : '' }}>Wisata Buatan</option>
            <option value="budaya" {{ ($a->jenis_wisata ?? '') === 'budaya' ? 'selected' : '' }}>Wisata Budaya</option>
        </select>
    </div>

    {{-- Kota --}}
    <div class="col-md-4 mb-3">
        <label class="form-label fw-semibold text-primary">Kota</label>
        <input type="text" name="kota" class="form-control" value="{{ $a->kota ?? '' }}"
            placeholder="Contoh: Surabaya">
    </div>

    {{-- Jarak Default --}}
    <div class="col-md-4 mb-3">
        <label class="form-label fw-semibold text-primary">Jarak Default (km) <span class="text-danger">*</span></label>
        <input type="number" name="jarak_default" class="form-control" value="{{ $a->jarak_default ?? '' }}"
            step="1" min="0" required>
        <small class="text-muted">Jarak dari titik referensi (integer)</small>
    </div>
</div>

{{-- Alamat --}}
<div class="mb-3">
    <label class="form-label fw-semibold text-primary">Alamat</label>
    <textarea name="alamat" class="form-control" rows="2">{{ $a->alamat ?? '' }}</textarea>
</div>

<div class="row">
    {{-- Latitude --}}
    <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold text-primary">Latitude <span class="text-danger">*</span></label>
        <input type="number" name="latitude" class="form-control" value="{{ $a->latitude ?? '' }}" step="any"
            required>
    </div>

    {{-- Longitude --}}
    <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold text-primary">Longitude <span class="text-danger">*</span></label>
        <input type="number" name="longitude" class="form-control" value="{{ $a->longitude ?? '' }}" step="any"
            required>
    </div>
</div>

{{-- Link Google Maps --}}
<div class="mb-3">
    <label class="form-label fw-semibold text-primary">Link Google Maps</label>
    <input type="url" name="link_gmaps" class="form-control" value="{{ $a->link_gmaps ?? '' }}"
        placeholder="https://maps.google.com/...">
</div>

<hr>

<h6 class="mb-3 mt-4 text-primary fw-bold">Kriteria Penilaian</h6>

<div class="row">
    {{-- Biaya Masuk --}}
    <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold text-primary">Biaya (Rp) <span class="text-danger">*</span></label>
        <input type="number" name="biaya_masuk" class="form-control" value="{{ $a->biaya_masuk ?? '' }}"
            min="0" required>
        <small class="text-muted">Masukkan dalam Rupiah</small>
    </div>

    {{-- Rating --}}
    <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold text-primary">Rating <span class="text-danger">*</span></label>
        <input type="number" name="rating" class="form-control" value="{{ $a->rating ?? '' }}" step="0.1"
            min="0" max="5" required>
        <small class="text-muted">Skala 0.0 - 5.0</small>
    </div>
</div>

<div class="row">
    {{-- Aksesibilitas --}}
    <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold text-primary">Aksesibilitas <span class="text-danger">*</span></label>
        <input type="number" name="aksesibilitas" class="form-control" value="{{ $a->aksesibilitas ?? '' }}"
            step="1" min="1" max="5" required>
    </div>

    {{-- Fasilitas --}}
    <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold text-primary">Fasilitas <span class="text-danger">*</span></label>
        <input type="number" name="fasilitas" class="form-control" value="{{ $a->fasilitas ?? '' }}"
            step="1" min="1" max="5" required>
    </div>
</div>

{{-- Deskripsi --}}
<div class="mb-3">
    <label class="form-label fw-semibold text-primary">Deskripsi</label>
    <textarea name="deskripsi" class="form-control" rows="3">{{ $a->deskripsi ?? '' }}</textarea>
</div>

<style>
    .text-primary {
        color: var(--d-contrast) !important;
    }
</style>
