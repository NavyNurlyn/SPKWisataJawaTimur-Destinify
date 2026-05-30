{{-- resources/views/kriteria/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Kriteria')
@section('page-title', 'Manajemen Data Kriteria')

@section('content')
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>Daftar Kriteria</h5>
            {{-- Add Kriteria Nonaktif --}}
            {{-- <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">
                <i class="bi bi-plus-lg"></i> Tambah Kriteria
            </button> --}}
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-header-secondary text-center">
                        <tr>
                            <th width="5%">No</th>
                            <th>Kode</th>
                            <th>Nama Kriteria</th>
                            <th>Atribut</th>
                            <th>Bobot</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kriterias as $index => $k)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $k->kode_kriteria }}</td>
                                <td>{{ $k->nama_kriteria }}</td>
                                <td class="text-center">
                                    <span
                                        class="badge {{ $k->atribut == 'Benefit' ? 'badge-primary' : 'badge-secondary' }}">
                                        {{ $k->atribut }}
                                    </span>
                                </td>
                                <td class="text-center">{{ $k->bobot ? number_format($k->bobot, 5) : '-' }}</td>
                                <td class="text-center">
                                    <button class="btn btn-2 btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#editModal{{ $k->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    {{-- Remove Kriteria Nonaktif --}}
                                    {{-- <form action="{{ route('kriteria.destroy', $k->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                                    </form> --}}
                                </td>
                            </tr>

                            <div class="modal fade" id="editModal{{ $k->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('kriteria.update', $k->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header" style="background: var(--d-secondary); color:black;">
                                                <h5 class="modal-title">Edit Kriteria</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Kode Kriteria</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $k->kode_kriteria }}" readonly
                                                        style="background-color:white; cursor:not-allowed;">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Kriteria</label>
                                                    <input type="text" name="nama_kriteria" class="form-control"
                                                        value="{{ $k->nama_kriteria }}" readonly
                                                        style="background-color:white; cursor:not-allowed;" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Atribut</label>
                                                    <select name="atribut" class="form-select" required>
                                                        <option value="Benefit"
                                                            {{ $k->atribut == 'Benefit' ? 'selected' : '' }}>Benefit
                                                        </option>
                                                        <option value="Cost"
                                                            {{ $k->atribut == 'Cost' ? 'selected' : '' }}>Cost</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-1">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada data kriteria.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
