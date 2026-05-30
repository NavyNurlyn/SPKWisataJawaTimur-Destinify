{{-- resources/views/rekomendasi/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Rekomendasi Wisata')
@section('page-title', 'Cari Rekomendasi Tempat Wisata')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                {{-- Alert jika AHP belum konsisten --}}
                @if (!$ahpKonsisten)
                    <div class="alert alert-d-danger">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <strong>Tidak Dapat Melanjutkan!</strong>
                        <p class="mb-2 mt-2">Pembobotan AHP belum konsisten (CR = {{ $cr ? number_format($cr, 5) : 'N/A' }}).
                        </p>
                        <p class="mb-0">Silakan lakukan <a href="{{ route('pembobotan.index') }}"
                                class="alert-link fw-bold">pembobotan kriteria</a> terlebih dahulu hingga konsisten (CR <
                                0.1) sebelum dapat melakukan pencarian rekomendasi.</p>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('pembobotan.index') }}" class="btn btn-warning btn-lg">
                            <i class="bi bi-arrow-left"></i> Kembali ke Pembobotan
                        </a>
                    </div>
                @else
                    {{-- Alert sukses AHP konsisten --}}
                    <div class="alert alert-d-success">
                        <i class="bi bi-check-circle-fill"></i>
                        <strong>Siap!</strong> Pembobotan AHP sudah konsisten (CR = {{ number_format($cr, 5) }}).
                        Anda dapat melanjutkan pencarian rekomendasi.
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <h4 class="mb-4">Masukkan Preferensi Anda</h4>

                            @if ($errors->any())
                                <div class="alert alert-d-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('rekomendasi.hitung') }}" method="POST">
                                @csrf

                                {{-- Info Box --}}
                                <div class="alert alert-d-info">
                                    <i class="bi bi-info-circle"></i>
                                    <strong>Panduan:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li><strong>Lokasi Anda:</strong> Masukkan koordinat untuk hasil yang lebih akurat
                                            berdasarkan jarak sebenarnya. Kosongkan jika ingin menggunakan jarak default
                                            sistem  (titik lokasi : UPN "Veteran" Jawa Timur).</li>
                                        <li><strong>Jenis Wisata:</strong> Pilih satu atau lebih jenis wisata yang Anda
                                            inginkan. Jika tidak dipilih, sistem akan menampilkan semua jenis.</li>
                                    </ul>
                                </div>

                                {{-- Input Koordinat --}}
                                <div class="card mb-4">
                                    <div class="card-header" style="background: var(--d-primary); color:black;">
                                        <h5 class="mb-0"><i class="bi bi-geo-alt"></i> Lokasi Anda (Opsional)</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Latitude</label>
                                                <input type="number" step="any" name="latitude"
                                                    class="form-control @error('latitude') is-invalid @enderror"
                                                    placeholder="Contoh: -7.3337212" value="{{ old('latitude') }}">
                                                @error('latitude')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <small class="text-muted">Koordinat lintang lokasi Anda</small>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Longitude</label>
                                                <input type="number" step="any" name="longitude"
                                                    class="form-control @error('longitude') is-invalid @enderror"
                                                    placeholder="Contoh: 112.7883247" value="{{ old('longitude') }}">
                                                @error('longitude')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <small class="text-muted">Koordinat bujur lokasi Anda</small>
                                            </div>
                                        </div>

                                        <div class="alert alert-d-info mb-0">
                                            <i class="bi bi-lightbulb"></i>
                                            <strong>Tips:</strong> Anda bisa mendapatkan koordinat dengan:
                                            <ol class="mb-0 mt-1">
                                                <li>Buka Google Maps di browser</li>
                                                <li>Klik kanan pada lokasi Anda</li>
                                                <li>Klik koordinat untuk menyalin (format: latitude, longitude)</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>

                                {{-- Filter Jenis Wisata --}}
                                <div class="card mb-4">
                                    <div class="card-header" style="background: var(--d-primary); color:black;">
                                        <h5 class="mb-0"><i class="bi bi-funnel"></i> Filter Jenis Wisata (Opsional)</h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-muted">Pilih jenis wisata yang Anda minati. Biarkan kosong untuk
                                            menampilkan semua jenis.</p>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input"
                                                        style="border: 1px solid var(--d-primary)" type="checkbox"
                                                        name="jenis_wisata[]" value="alam" id="jenis_alam"
                                                        {{ is_array(old('jenis_wisata')) && in_array('alam', old('jenis_wisata')) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="jenis_alam">
                                                        <i class="bi bi-tree text-success"></i> Wisata Alam
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input"
                                                        style="border: 1px solid var(--d-primary)" type="checkbox"
                                                        name="jenis_wisata[]" value="buatan" id="jenis_buatan"
                                                        {{ is_array(old('jenis_wisata')) && in_array('buatan', old('jenis_wisata')) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="jenis_buatan">
                                                        <i class="bi bi-building text-primary"></i> Wisata Buatan
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input"
                                                        style="border: 1px solid var(--d-primary)" type="checkbox"
                                                        name="jenis_wisata[]" value="budaya" id="jenis_budaya"
                                                        {{ is_array(old('jenis_wisata')) && in_array('budaya', old('jenis_wisata')) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="jenis_budaya">
                                                        <i class="bi bi-bank text-warning"></i> Wisata Budaya
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Tombol Submit --}}
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-search"></i> Cari Rekomendasi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    {{-- Modal Loading --}}
    <div class="modal fade" id="loadingModal" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4">
                <div class="d-flex flex-column justify-content-center align-items-center">

                    <div class="spinner-border text-primary mb-3" style="width: 4rem; height: 4rem;">
                    </div>

                    <p class="text-muted mb-0">
                        <span id="loadingTimer" class="fw-bold">0</span> detik
                    </p>

                    <h5 class="mb-2 mt-2">Sedang menghitung rekomendasi...</h5>

                    <p class="text-muted mb-0">Mohon tunggu</p>

                </div>
            </div>
        </div>
    </div>

    <script>
        let timerInterval;
        let seconds = 0;

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form[action="{{ route('rekomendasi.hitung') }}"]');

            form.addEventListener('submit', function() {

                // Reset timer
                seconds = 0;
                document.getElementById('loadingTimer').textContent = seconds;

                // Mulai timer
                timerInterval = setInterval(() => {
                    seconds++;
                    document.getElementById('loadingTimer').textContent = seconds;
                }, 1000);

                // Tampilkan modal loading
                const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
                loadingModal.show();
            });
        });
    </script>
@endsection
