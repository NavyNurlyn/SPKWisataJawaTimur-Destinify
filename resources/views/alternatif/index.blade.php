{{-- resources/views/alternatif/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Alternatif')
@section('page-title', 'Manajemen Data Tempat Wisata')

@section('content')
    <div class="container-fluid">
        {{-- Pesan sukses --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Header & tombol tambah --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>Daftar Tempat Wisata</h5>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">
                <i class="bi bi-plus-lg"></i> Tambah Wisata
            </button>
        </div>

        {{-- Tabel Wisata --}}
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-header-primary text-center">
                            <tr>
                                <th width="3%">No</th>
                                <th width="5%">Kode</th>
                                <th>Nama Wisata</th>
                                <th width="8%">Jenis</th>
                                <th width="10%">Jarak (km)</th>
                                <th width="10%">Biaya</th>
                                <th width="7%">Rating</th>
                                <th width="12%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($alternatifs as $index => $alternatif)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $alternatif->kode_alternatif }}</td>
                                    <td>
                                        <strong>{{ $alternatif->nama_wisata }}</strong>
                                        @if ($alternatif->kota)
                                            <br><small class="text-muted">{{ $alternatif->kota }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="badge 
                                                {{ $alternatif->jenis_wisata == 'alam'
                                                    ? 'badge-secondary'
                                                    : ($alternatif->jenis_wisata == 'buatan'
                                                        ? 'badge-accent'
                                                        : 'badge-primary') }}">
                                            {{ ucfirst($alternatif->jenis_wisata) }}
                                        </span>
                                    </td>
                                    <td class="text-center">{{ number_format($alternatif->jarak_default, 0) }}</td>
                                    <td class="text-end">Rp {{ number_format($alternatif->biaya_masuk, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-indigo-400 text-dark border">
                                            ⭐ {{ number_format($alternatif->rating, 1) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-1 btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#detailModal{{ $alternatif->id }}">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-2 btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#editModal{{ $alternatif->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('alternatif.destroy', $alternatif->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                {{-- Modal Detail --}}
                                <div class="modal fade" id="detailModal{{ $alternatif->id }}" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header" style="background: var(--d-secondary); color:black;">
                                                <h5 class="modal-title">Detail: {{ $alternatif->nama_wisata }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p><strong>Kode:</strong> {{ $alternatif->kode_alternatif }}</p>
                                                        <p><strong>Jenis:</strong> {{ ucfirst($alternatif->jenis_wisata) }}
                                                        </p>
                                                        <p><strong>Kota:</strong> {{ $alternatif->kota ?? '-' }}</p>
                                                        <p><strong>Alamat:</strong> {{ $alternatif->alamat ?? '-' }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>Latitude:</strong> {{ $alternatif->latitude }}</p>
                                                        <p><strong>Longitude:</strong> {{ $alternatif->longitude }}</p>
                                                        <p><strong>Jarak Default:</strong> {{ $alternatif->jarak_default }}
                                                            km
                                                        </p>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <p><strong>Biaya:</strong><br>Rp
                                                            {{ number_format($alternatif->biaya_masuk, 0, ',', '.') }}</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <p><strong>Aksesibilitas:</strong><br>{{ number_format($alternatif->aksesibilitas, 1) }}/5
                                                        </p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <p><strong>Fasilitas:</strong><br>{{ number_format($alternatif->fasilitas, 1) }}/5
                                                        </p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <p><strong>Rating:</strong><br>{{ number_format($alternatif->rating, 1) }}/5
                                                        </p>
                                                    </div>
                                                </div>
                                                <hr>
                                                <p><strong>Deskripsi:</strong></p>
                                                <p>{{ $alternatif->deskripsi ?? '-' }}</p>

                                                @if ($alternatif->link_gmaps)
                                                    <a href="{{ $alternatif->link_gmaps }}" target="_blank"
                                                        class="btn btn-primary">
                                                        <i class="bi bi-map"></i> Lihat di Google Maps
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Modal Edit --}}
                                <div class="modal fade" id="editModal{{ $alternatif->id }}" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <form action="{{ route('alternatif.update', $alternatif->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header" style="background: var(--d-accent); color:black;">
                                                    <h5 class="modal-title">Edit Wisata</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    @include('alternatif.form', [
                                                        'mode' => 'edit',
                                                        'alternatif' => $alternatif,
                                                    ])
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Belum ada data wisata.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Tambah Wisata --}}
    <div class="modal fade" id="tambahModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('alternatif.store') }}" method="POST">
                    @csrf
                    <div class="modal-header" style="background: var(--d-primary); color:black;">
                        <h5 class="modal-title">Tambah Destinasi Wisata</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @include('alternatif.form', ['mode' => 'create'])
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
