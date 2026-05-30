{{-- resources/views/pembobotan/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Pembobotan Kriteria')
@section('page-title', 'Input Nilai Perbandingan (AHP)')

@section('content')
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @elseif (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('pembobotan.update') }}" method="POST" class="row g-3 align-items-end">
                    @csrf

                    <div class="col-md-3">
                        <label class="form-label">Kriteria 1</label>
                        <select name="kriteria1" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            @foreach ($kriterias as $k)
                                <option value="{{ $k->kode_kriteria }}">{{ $k->kode_kriteria }} -
                                    {{ $k->nama_kriteria }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Nilai Perbandingan</label>
                        <select name="nilai" class="form-select" required>
                            <option value="">-- Pilih Nilai --</option>
                            <option value="1">1 - Sama penting dengan</option>
                            <option value="2">2 - Mendekati sedikit lebih penting dari</option>
                            <option value="3">3 - Sedikit lebih penting dari</option>
                            <option value="4">4 - Mendekati lebih penting dari</option>
                            <option value="5">5 - Lebih penting dari</option>
                            <option value="6">6 - Mendekati sangat penting dari</option>
                            <option value="7">7 - Sangat penting dari</option>
                            <option value="8">8 - Mendekati mutlak dari</option>
                            <option value="9">9 - Mutlak sangat penting dari</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Kriteria 2</label>
                        <select name="kriteria2" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            @foreach ($kriterias as $k)
                                <option value="{{ $k->kode_kriteria }}">{{ $k->kode_kriteria }} -
                                    {{ $k->nama_kriteria }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-1 w-100">
                            <i class="bi bi-pencil-square"></i> Ubah
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">Matriks Perbandingan Kriteria</h5>
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-header-secondary text-center">
                            <tr>
                                <th>Kode</th>
                                @foreach ($kriterias as $k)
                                    <th>{{ $k->kode_kriteria }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kriterias as $k1)
                                <tr>
                                    <th class="cell-d-secondary">{{ $k1->kode_kriteria }}</th>
                                    @foreach ($kriterias as $k2)
                                        @php
                                            $value = $matriks[$k1->kode_kriteria][$k2->kode_kriteria] ?? '-';
                                            $color =
                                                $k1->kode_kriteria == $k2->kode_kriteria
                                                    ? 'cell-d-1'
                                                    : ($loop->parent->index < $loop->index
                                                        ? 'cell-d-3'
                                                        : 'table-success-subtle');
                                        @endphp
                                        <td class="{{ $color }}">
                                            {{ is_numeric($value) ? number_format($value, 2) : $value }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="text-end mt-3">
                    <form action="{{ route('pembobotan.hitung') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-calculator"></i> Hitung Pembobotan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid" style="margin-top: 30px;">

        {{-- Pesan sukses / error / warning --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Matriks Perbandingan Kriteria --}}
        <div class="card mb-3" id="hasil-ahp">
            <div class="card-header" style="background: var(--d-primary); color:black;">
                <h5 class="mb-0">Matriks Perbandingan Kriteria</h5>
            </div>
            <div class="card-body">
                @if (empty($matriks))
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Belum ada data matriks. Silakan lakukan pembobotan kriteria
                        terlebih dahulu.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Kriteria</th>
                                    @foreach ($kriterias as $k)
                                        <th>{{ $k->kode_kriteria }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kriterias as $k1)
                                    <tr>
                                        <td><strong>{{ $k1->kode_kriteria }}</strong></td>
                                        @foreach ($kriterias as $k2)
                                            @php
                                                $val = $matriks[$k1->kode_kriteria][$k2->kode_kriteria] ?? 0;
                                            @endphp
                                            <td>{{ number_format($val, 5) }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach

                                {{-- Baris Total Kolom --}}
                                <tr class="fw-bold" style="background-color: #f5f5f5;">
                                    <td>Total Kolom</td>
                                    @foreach ($kriterias as $k)
                                        <td>{{ number_format($totalKolom[$k->kode_kriteria] ?? 0, 5) }}</td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- Matriks Normalisasi --}}
        <div class="card mb-3">
            <div class="card-header" style="background: var(--d-secondary); color:black;">
                <h5 class="mb-0">Matriks Normalisasi</h5>
            </div>
            <div class="card-body">
                @if (empty($normalisasi))
                    <div class="alert alert-info">Belum ada data normalisasi.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    @foreach ($kriterias as $k)
                                        <th>{{ $k->kode_kriteria }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kriterias as $k1)
                                    <tr>
                                        <th>{{ $k1->kode_kriteria }}</th>
                                        @foreach ($kriterias as $k2)
                                            <td>{{ number_format($normalisasi[$k1->kode_kriteria][$k2->kode_kriteria], 5) }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- Matriks Bobot Prioritas --}}
        <div class="card mb-3">
            <div class="card-header" style="background: var(--d-accent); color:black;">
                <h5 class="mb-0">Matriks Bobot Prioritas Kriteria</h5>
            </div>
            <div class="card-body">
                @if (empty($bobot))
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Belum ada data bobot. Silakan hitung pembobotan AHP terlebih
                        dahulu.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Kriteria</th>
                                    <th>Bobot</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bobot as $k => $b)
                                    <tr>
                                        <td>{{ $k }}</td>
                                        <td>{{ number_format($b, 5) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- Matriks Konsistensi --}}
        <div class="card mb-3">

            @php
                $headerColor = $ahpKonsisten ? 'var(--d-secondary)' : 'var(--d-danger)';
                $headerText = 'var(--d-contrast)';
            @endphp

            <div class="card-header" style="background: {{ $headerColor }}; color: {{ $headerText }};">
                <h5 class="mb-0 d-flex align-items-center">
                    @if ($ahpKonsisten)
                        <i class="bi bi-check-circle-fill fs-3 fw-bold me-2"></i>
                    @else
                        <i class="bi bi-exclamation-triangle-fill fs-3 fw-bold me-2"></i>
                    @endif
                    Hasil Konsistensi AHP
                </h5>
            </div>

            <div class="card-body">
                @if (!$lamdaMax)
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle fs-5"></i> Belum ada hasil perhitungan. Silakan hitung pembobotan
                        AHP
                        terlebih dahulu.
                    </div>
                @else
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>λ max (Lambda Max):</strong> {{ number_format($lamdaMax, 5) }}
                            </p>
                            <p class="mb-2"><strong>CI (Consistency Index):</strong> {{ number_format($ci, 5) }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>RI (Random Index):</strong> {{ number_format($ri, 5) }}</p>
                            <p class="mb-2"><strong>CR (Consistency Ratio):</strong> {{ number_format($cr, 5) }}</p>
                        </div>
                    </div>

                    <hr>

                    {{-- Box hasil --}}
                    <div class="text-center p-3 rounded"
                        style="background-color: {{ $ahpKonsisten ? 'var(--d-bg)' : '#fff3cd' }};">

                        @if ($ahpKonsisten)
                            <h5 class="text-success mb-2">
                                <i class="bi bi-check-circle-fill fs-3 fw-bold me-1"></i>
                                Pembobotan Konsisten
                            </h5>
                            <p class="mb-0">CR = {{ number_format($cr, 5) }} < 0.1. Anda dapat melanjutkan ke pencarian
                                    rekomendasi.</p>
                                @else
                                    <h5 class="text-warning mb-2">
                                        <i class="bi bi-exclamation-triangle-fill fs-3 fw-bold me-1"></i>
                                        Pembobotan Tidak Konsisten
                                    </h5>
                                    <p class="mb-0">CR = {{ number_format($cr, 5) }} ≥ 0.1. Silakan ubah nilai
                                        perbandingan kriteria pada halaman pembobotan.</p>
                        @endif
                    </div>

                    {{-- Tombol navigasi --}}
                    @if ($ahpKonsisten)
                        <div class="text-end mt-3">
                            <a href="{{ route('rekomendasi.index') }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-search"></i> Lanjut ke Pencarian Rekomendasi
                            </a>
                        </div>
                    @else
                        <div class="text-end mt-3">
                            <a href="{{ route('pembobotan.index') }}" class="btn btn-warning">
                                <i class="bi bi-arrow-left"></i> Kembali ke Pembobotan
                            </a>
                        </div>
                    @endif

                @endif
            </div>
        </div>
    </div>

    @if (session('scroll'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const el = document.getElementById("hasil-ahp");
                if (el) {
                    el.scrollIntoView({
                        behavior: "smooth",
                        block: "start"
                    });
                }
            });
        </script>
    @endif

@endsection
