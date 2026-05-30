{{-- resources/views/rekomendasi/hasil.blade.php --}}
@extends('layouts.app')

@section('title', 'Hasil Rekomendasi')
@section('page-title', 'Hasil Rekomendasi Wisata')

@section('content')
    <div class="container-fluid">
        {{-- Header dengan info dan tombol --}}
        <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap">
            <a href="{{ route('rekomendasi.index') }}" class="btn btn-secondary mb-2">
                <i class="bi bi-arrow-left"></i> Cari Ulang
            </a>

            <div class="d-flex gap-2 flex-wrap">
                @if (isset($useUserLocation))
                    <div class="badge {{ $useUserLocation ? 'bg-success' : 'bg-info' }} fs-6">
                        <i class="bi bi-geo-alt-fill"></i>
                        {{ $useUserLocation ? 'Berdasarkan Lokasi Anda' : 'Berdasarkan Jarak Default' }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Tabs Navigation --}}
        <ul class="nav nav-tabs mb-3" id="hasilTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="rekomendasi-tab" data-bs-toggle="tab" data-bs-target="#rekomendasi"
                    type="button" role="tab">
                    <i class="bi bi-list-stars"></i> Hasil Rekomendasi
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="perhitungan-tab" data-bs-toggle="tab" data-bs-target="#perhitungan"
                    type="button" role="tab">
                    <i class="bi bi-calculator"></i> Detail Perhitungan TOPSIS
                </button>
            </li>
        </ul>

        {{-- Tabs Content --}}
        <div class="tab-content" id="hasilTabsContent">

            {{-- Tab 1: Hasil Rekomendasi --}}
            <div class="tab-pane fade show active" id="rekomendasi" role="tabpanel">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle-fill"></i>
                    <strong>Hasil Pencarian:</strong> Ditemukan {{ $hasilRanking->count() }} destinasi wisata
                    yang sesuai dengan preferensi Anda, diurutkan berdasarkan nilai preferensi tertinggi.
                </div>

                @if ($hasilRanking->isEmpty())
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        Tidak ada wisata yang sesuai dengan filter Anda. Silakan ubah filter pencarian.
                    </div>
                @else
                    <div class="row">
                        @foreach ($hasilRanking as $hasil)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card shadow-sm h-100 {{ $hasil->ranking <= 3 ? 'border-primary' : '' }}">
                                    {{-- Header Card dengan Ranking --}}
                                    <div
                                        class="card-header {{ $hasil->ranking == 1 ? 'bg-warning' : ($hasil->ranking <= 3 ? 'bg-primary' : 'bg-secondary') }} text-white">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">
                                                <span
                                                    class="badge {{ $hasil->ranking == 1 ? 'bg-dark' : 'bg-light' }} text-{{ $hasil->ranking == 1 ? 'warning' : 'dark' }}">
                                                    #{{ $hasil->ranking }}
                                                </span>
                                            </h5>
                                            @if ($hasil->ranking == 1)
                                                <i class="bi bi-trophy-fill fs-4"></i>
                                            @elseif ($hasil->ranking <= 3)
                                                <i class="bi bi-star-fill fs-5"></i>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Body Card --}}
                                    <div class="card-body">
                                        <h5 class="card-title text-primary">{{ $hasil->alternatif->nama_wisata }}</h5>

                                        {{-- Info Utama --}}
                                        <div class="mb-3">
                                            <p class="mb-2">
                                                <i class="bi bi-geo-alt-fill text-danger"></i>
                                                <strong>Jarak:</strong> {{ number_format($hasil->jarak_km, 2) }} km
                                            </p>

                                            <p class="mb-2">
                                                <i class="bi bi-tag-fill text-info"></i>
                                                <strong>Jenis:</strong>
                                                <span
                                                    class="badge bg-info">{{ ucfirst($hasil->alternatif->jenis_wisata) }}</span>
                                            </p>

                                            @if ($hasil->alternatif->kota)
                                                <p class="mb-2">
                                                    <i class="bi bi-building text-secondary"></i>
                                                    <strong>Kota:</strong> {{ $hasil->alternatif->kota }}
                                                </p>
                                            @endif
                                        </div>

                                        <hr>

                                        {{-- Detail Kriteria --}}
                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <small class="text-muted">
                                                    <i class="bi bi-cash"></i> Biaya:<br>
                                                    <strong>Rp
                                                        {{ number_format($hasil->alternatif->biaya_masuk, 0, ',', '.') }}</strong>
                                                </small>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">
                                                    <i class="bi bi-star-fill text-warning"></i> Rating:<br>
                                                    <strong>{{ number_format($hasil->alternatif->rating, 1) }}/5</strong>
                                                </small>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">
                                                    <i class="bi bi-map"></i> Aksesibilitas:<br>
                                                    <strong>{{ number_format($hasil->alternatif->aksesibilitas, 1) }}/5</strong>
                                                </small>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">
                                                    <i class="bi bi-building"></i> Fasilitas:<br>
                                                    <strong>{{ number_format($hasil->alternatif->fasilitas, 1) }}/5</strong>
                                                </small>
                                            </div>
                                        </div>

                                        <hr>

                                        {{-- Deskripsi --}}
                                        @if ($hasil->alternatif->deskripsi)
                                            <p class="small text-muted mb-3">
                                                {{ Str::limit($hasil->alternatif->deskripsi, 150) }}
                                            </p>
                                        @endif

                                        {{-- Link Google Maps --}}
                                        @if ($hasil->alternatif->link_gmaps)
                                            <a href="{{ $hasil->alternatif->link_gmaps }}" target="_blank"
                                                class="btn btn-outline-primary btn-sm w-100 mb-2">
                                                <i class="bi bi-map"></i> Lihat di Google Maps
                                            </a>
                                        @endif

                                        {{-- Nilai Preferensi --}}
                                        <div class="text-center mt-3 p-2 bg-light rounded">
                                            <small class="text-muted">Skor Preferensi:</small><br>
                                            <strong
                                                class="text-primary">{{ number_format($hasil->nilai_preferensi, 5) }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Tab 2: Detail Perhitungan TOPSIS --}}
            <div class="tab-pane fade" id="perhitungan" role="tabpanel">

                {{-- 1. Bobot Kriteria --}}
                <div class="card mb-3">
                    <div class="card-header bg-success text-white" id="bobot">
                        <h5 class="mb-0"><i class="bi bi-percent"></i> Bobot Kriteria (dari AHP)</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead class="table-light">
                                    <tr>
                                        @foreach ($topsisData['kriterias'] as $k)
                                            <th class="text-center">
                                                {{ $k->kode_kriteria }} ({{ $k->nama_kriteria }})
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        @foreach ($topsisData['kriterias'] as $k)
                                            <td class="text-center">
                                                {{ number_format($topsisData['bobot'][$k->id] ?? 0, 5) }}</td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- 2. Matriks Keputusan --}}
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white" id="data-alternatif">
                        <h5 class="mb-0"><i class="bi bi-table"></i> Data Alternatif</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Alternatif</th>
                                        @foreach ($topsisData['kriterias'] as $k)
                                            <th class="text-center">
                                                {{ $k->kode_kriteria }} ({{ $k->nama_kriteria }})
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($topsisData['matriksKeputusan'] as $altId => $vals)
                                        @php
                                            $alt = $topsisData['alternatifs']->firstWhere('id', $altId);
                                        @endphp
                                        <tr>
                                            <td><strong>{{ $alt->kode_alternatif ?? 'A' . $altId }}</strong></td>
                                            @foreach ($topsisData['kriterias'] as $k)
                                                <td class="text-center">
                                                    @php
                                                        $value = $vals[$k->id] ?? 0;

                                                        // Format khusus rating (C5)
                                                        $formatted =
                                                            $k->kode_kriteria === 'C5'
                                                                ? number_format($value, 1) // satu angka di belakang koma
                                                                : number_format($value, 0); // bulat
                                                    @endphp

                                                    {{ $formatted }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- 3. Matriks Normalisasi --}}
                <div class="card mb-3">
                    <div class="card-header bg-info text-white" id="normalisasi">
                        <h5 class="mb-0"><i class="bi bi-calculator"></i> Matriks Keputusan Normalisasi</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Alternatif</th>
                                        @foreach ($topsisData['kriterias'] as $k)
                                            <th class="text-center">{{ $k->kode_kriteria }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($topsisData['normalisasi'] as $altId => $vals)
                                        @php
                                            $alt = $topsisData['alternatifs']->firstWhere('id', $altId);
                                        @endphp
                                        <tr>
                                            <td><strong>{{ $alt->kode_alternatif ?? 'A' . $altId }}</strong></td>
                                            @foreach ($topsisData['kriterias'] as $k)
                                                <td class="text-center">{{ number_format($vals[$k->id] ?? 0, 5) }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- 4. Matriks Terbobot --}}
                <div class="card mb-3">
                    <div class="card-header bg-warning text-dark" id="terbobot">
                        <h5 class="mb-0"><i class="bi bi-graph-up"></i> Matriks Keputusan Normalisasi Terbobot</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Alternatif</th>
                                        @foreach ($topsisData['kriterias'] as $k)
                                            <th class="text-center">{{ $k->kode_kriteria }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($topsisData['terbobot'] as $altId => $vals)
                                        @php
                                            $alt = $topsisData['alternatifs']->firstWhere('id', $altId);
                                        @endphp
                                        <tr>
                                            <td><strong>{{ $alt->kode_alternatif ?? 'A' . $altId }}</strong></td>
                                            @foreach ($topsisData['kriterias'] as $k)
                                                <td class="text-center">{{ number_format($vals[$k->id] ?? 0, 5) }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- 5. Solusi Ideal --}}
                <div class="card mb-3">
                    <div class="card-header bg-secondary text-white" id="ideal">
                        <h5 class="mb-0"><i class="bi bi-star"></i> Solusi Ideal Positif & Negatif</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kriteria</th>
                                        @foreach ($topsisData['kriterias'] as $k)
                                            <th class="text-center">{{ $k->kode_kriteria }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Ideal Positif (A+)</strong></td>
                                        @foreach ($topsisData['kriterias'] as $k)
                                            <td class="text-center bg-success-subtle">
                                                {{ number_format($topsisData['idealPos'][$k->id] ?? 0, 5) }}</td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td><strong>Ideal Negatif (A-)</strong></td>
                                        @foreach ($topsisData['kriterias'] as $k)
                                            <td class="text-center bg-danger-subtle">
                                                {{ number_format($topsisData['idealNeg'][$k->id] ?? 0, 5) }}</td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- 6. Jarak & Preferensi --}}
                <div class="card mb-3">
                    <div class="card-header bg-dark text-white" id="jarak">
                        <h5 class="mb-0"><i class="bi bi-rulers"></i> Jarak ke Solusi Ideal & Nilai Preferensi</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Alternatif</th>
                                        <th class="text-center">D+ (Jarak ke A+)</th>
                                        <th class="text-center">D- (Jarak ke A-)</th>
                                        <th class="text-center">Nilai Preferensi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($detailJarakPreferensi as $item)
                                        <tr>
                                            <td><strong>{{ $item['kode_alternatif'] }}</strong></td>
                                            <td class="text-center">{{ number_format($item['d_plus'], 5) }}</td>
                                            <td class="text-center">{{ number_format($item['d_minus'], 5) }}</td>
                                            <td class="text-center">
                                                <strong>{{ number_format($item['nilai_preferensi'], 5) }}</strong>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- 7. Ranking Akhir --}}
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white" id="ranking">
                        <h5 class="mb-0"><i class="bi bi-trophy"></i> Ranking Alternatif</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center" style="width: 10%;">Rank</th>
                                        <th class="text-center" style="width: 15%;">Kode</th>
                                        <th style="width: 40%;">Nama Alternatif</th>
                                        <th class="text-center" style="width: 15%;">Jenis</th>
                                        <th class="text-center" style="width: 20%;">Nilai Preferensi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($topsisData['hasilRanking'] as $item)
                                        <tr class="{{ $item['ranking'] <= 3 ? 'table-success' : '' }}">
                                            <td class="text-center">
                                                <strong>
                                                    @if ($item['ranking'] == 1)
                                                        <span class="badge bg-warning text-dark">🏆
                                                            #{{ $item['ranking'] }}</span>
                                                    @elseif($item['ranking'] <= 3)
                                                        <span class="badge bg-primary">⭐ #{{ $item['ranking'] }}</span>
                                                    @else
                                                        #{{ $item['ranking'] }}
                                                    @endif
                                                </strong>
                                            </td>
                                            <td class="text-center">{{ $item['kode_alternatif'] }}</td>
                                            <td>{{ $item['nama_alternatif'] }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-info">{{ ucfirst($item['jenis_wisata']) }}</span>
                                            </td>
                                            <td class="text-center">
                                                <strong>{{ number_format($item['nilai_preferensi'], 5) }}</strong>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        .card {
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .border-primary {
            border-width: 2px !important;
        }

        .nav-tabs .nav-link {
            color: #495057;
            font-weight: 500;
        }

        .nav-tabs .nav-link.active {
            color: #0d6efd;
            font-weight: 600;
        }
    </style>

    <!-- Floating Quick Navigation -->
    <div id="quickNav" class="quick-nav">
        <button class="quick-nav-btn">
            <i class="bi bi-list"></i>
        </button>

        <div class="quick-nav-menu shadow">
            <a href="#bobot">Bobot Kriteria</a>
            <a href="#data-alternatif">Data Alternatif</a>
            <a href="#normalisasi">Normalisasi</a>
            <a href="#terbobot">Terbobot</a>
            <a href="#ideal">Solusi Ideal</a>
            <a href="#jarak">Jarak & Preferensi</a>
            <a href="#ranking">Ranking Akhir</a>
        </div>
    </div>

    <style>
        .quick-nav {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
        }

        .quick-nav-btn {
            background: #0d6efd;
            color: white;
            border: none;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            font-size: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            transition: 0.2s;
        }

        .quick-nav-btn:hover {
            transform: scale(1.1);
        }

        .quick-nav-menu {
            display: none;
            position: absolute;
            bottom: 60px;
            right: 0;
            background: white;
            border-radius: 10px;
            padding: 10px 0;
            min-width: 180px;
        }

        .quick-nav-menu a {
            display: block;
            padding: 8px 15px;
            text-decoration: none;
            color: #333;
            font-size: 14px;
        }

        .quick-nav-menu a:hover {
            background: #f0f0f0;
        }

        .quick-nav.open .quick-nav-menu {
            display: block;
        }
    </style>

    <script>
        document.querySelector('.quick-nav-btn').addEventListener('click', function() {
            document.querySelector('#quickNav').classList.toggle('open');
        });
    </script>

@endsection
