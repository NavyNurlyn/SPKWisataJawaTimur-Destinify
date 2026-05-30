{{-- resources/views/dashboard/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard Destinify')
@section('page-title', 'Dashboard Destinify')

@section('content')
    <div class="container-fluid">
        <h4 class="mb-4 text-dark fw-semibold">Selamat datang di Destinify - Sistem Rekomendasi Wisata Jawa Timur</h4>

        {{-- Alert Status AHP --}}
        @if ($ahpKonsisten)
            <div class="alert alert-glass alert-dismissible fade show">
                <i class="bi bi-check-circle-fill icon-soft-green"></i>
                <strong>Sistem Siap!</strong> Pembobotan AHP sudah konsisten.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @else
            <div class="alert alert-glass alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle-fill icon-soft-yellow"></i>
                <strong>Perhatian!</strong> Pembobotan AHP belum konsisten.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4 mb-4">
            {{-- Statistik Card --}}
            <div class="col-md-3 col-sm-6">
                <div class="card glass-card text-center p-4 h-100">
                    <i class="bi bi-map-fill display-3 icon-soft-blue mb-3"></i>
                    <h6 class="mb-2">Tempat Wisata</h6>
                    <h3 class="text-dark">{{ $alternatifCount }}</h3>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="card glass-card text-center p-4 h-100">
                    <i class="bi bi-list-check display-3 icon-soft-green mb-3"></i>
                    <h6 class="mb-2">Kriteria</h6>
                    <h3 class="text-dark">{{ $kriteriaCount }}</h3>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="card glass-card text-center p-4 h-100">
                    <i class="bi bi-calculator-fill display-3 icon-soft-yellow mb-3"></i>
                    <h6 class="mb-2">Status AHP</h6>
                    <h3>
                        @if ($ahpKonsisten)
                            <i class="bi bi-check-circle-fill icon-soft-green">
                                <br><span class="fs-6">Konsisten</span>
                            </i>
                        @else
                            <i class="bi bi-x-circle-fill icon-soft-pink">
                                <br><span class="fs-6">Belum Konsisten</span>
                            </i>
                        @endif
                    </h3>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="card glass-card text-center p-4 h-100">
                    <i class="bi bi-star-fill display-3 icon-soft-lavender mb-3"></i>
                    <h6 class="mb-2">Rekomendasi</h6>
                    <a href="{{ route('rekomendasi.index') }}" class="btn btn-glass mt-2">
                        {{ $ahpKonsisten ? 'Cari Sekarang' : 'Belum Tersedia' }}
                    </a>
                </div>
            </div>
        </div>

        {{-- Menu Navigasi --}}
        <h5 class="mb-3 fw-semibold text-dark">Menu Utama</h5>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card glass-card">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-map-fill display-4 icon-soft-blue mb-3"></i>
                        <h5>Data Wisata</h5>
                        <p class="text-muted">Kelola data tempat wisata dan nilai kriteria</p>
                        <a href="{{ route('alternatif.index') }}" class="btn btn-glass">Kelola</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card glass-card">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-list-check display-4 icon-soft-green mb-3"></i>
                        <h5>Kriteria</h5>
                        <p class="text-muted">Kelola kriteria penilaian</p>
                        <a href="{{ route('kriteria.index') }}" class="btn btn-glass">Kelola</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card glass-card">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-calculator-fill display-4 icon-soft-yellow mb-3"></i>
                        <h5>Perhitungan AHP</h5>
                        <p class="text-muted">Hitung bobot kriteria dengan AHP</p>
                        <a href="{{ route('pembobotan.index') }}" class="btn btn-glass">Hitung</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card glass-card">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-search display-4 icon-soft-lavender mb-3"></i>
                        <h5>Cari Rekomendasi</h5>
                        <p class="text-muted">{{ $ahpKonsisten ? 'Mulai cari wisata terbaik' : 'Selesaikan AHP dulu' }}</p>

                        <a href="{{ route('rekomendasi.index') }}" class="btn btn-glass"
                            {{ !$ahpKonsisten ? 'disabled' : '' }}>
                            {{ $ahpKonsisten ? 'Mulai' : 'Belum Siap' }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <style>
        /* === GLASS CARD === */
        .glass-card {
            background: rgba(255, 255, 255, 0.45);
            border: 1px solid rgba(255, 255, 255, 0.35);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-radius: 18px !important;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
            transition: .25s ease;
        }

        .glass-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 26px rgba(0, 0, 0, 0.12);
        }

        /* === ICON COLORS (soft pastel) === */
        .icon-soft-lavender {
            color: #6e77ff !important;
        }

        .icon-soft-blue {
            color: #5ab8ff !important;
        }

        .icon-soft-yellow {
            color: #ffd966 !important;
        }

        .icon-soft-pink {
            color: #ff8ba7 !important;
        }

        .icon-soft-green {
            color: #67d9a7 !important;
        }

        /* === ALERT GLASS === */
        .alert-glass {
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.55) !important;
            border: 1px solid rgba(255, 255, 255, 0.45) !important;
            backdrop-filter: blur(12px);
            color: #333 !important;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.06);
        }

        /* === BUTTON PASTEL === */
        .btn-glass {
            background: rgba(255, 255, 255, 0.45);
            border: 1px solid rgba(255, 255, 255, 0.55);
            backdrop-filter: blur(10px);
            color: #4751c4;
        }

        .btn-glass:hover {
            background: rgba(255, 255, 255, 0.7);
            color: #2c2c90;
        }
    </style>

@endsection
