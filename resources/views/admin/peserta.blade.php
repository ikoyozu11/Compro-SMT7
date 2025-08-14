@extends('layout.app')
@section('title', 'Daftar Peserta Magang')
@section('content')
<style>
    .badge {
        font-size: 0.8rem;
        padding: 0.5rem 0.75rem;
    }
    .table td {
        vertical-align: middle;
    }
    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }
    .card-stats {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
</style>
<div class="container">
    <h2>Daftar Peserta Magang</h2>
    <p class="text-muted mb-4">Kelola data peserta magang yang sudah diterima dan sedang menjalani program magang</p>
    
    {{-- Card Statistik --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-2">
            <div class="card card-stats text-center">
                <div class="card-body">
                    <h6 class="card-title mb-1">Total Peserta</h6>
                    <h4 class="mb-0">{{ $totalPeserta }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title mb-1">Sedang Magang</h6>
                    <h4 class="mb-0 text-success">{{ $sedangMagang }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title mb-1">Selesai Magang</h6>
                    <h4 class="mb-0 text-info">{{ $selesaiMagang }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title mb-1">Lokasi Aktif</h6>
                    <h4 class="mb-0 text-warning">{{ $lokasiAktif }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        {{-- Filter Lokasi --}}
        <form method="GET" action="{{ route('admin.peserta') }}" id="filterForm">
            <div class="d-flex align-items-center">
                <select name="lokasi" id="lokasi" class="form-select me-2" onchange="this.form.submit()">
                    <option value="all" {{ $selectedLokasi=='all'?'selected':'' }}>Semua Lokasi</option>
                    @foreach($lokasi as $l)
                        <option value="{{ $l->id }}" {{ $selectedLokasi==$l->id?'selected':'' }}>
                            {{ $l->bidang }} - {{ $l->tim }}
                        </option>
                    @endforeach
                </select>
                
                <select name="status" id="status" class="form-select me-2" onchange="this.form.submit()">
                    <option value="all" {{ $selectedStatus=='all'?'selected':'' }}>Semua Status</option>
                    <option value="aktif" {{ $selectedStatus=='aktif'?'selected':'' }}>Sedang Magang</option>
                    <option value="selesai" {{ $selectedStatus=='selesai'?'selected':'' }}>Selesai Magang</option>
                </select>
            </div>
        </form>

        {{-- Tombol Urutkan --}}
        @php
            $currentSort = request('sort', 'desc');
            $nextSort = $currentSort === 'desc' ? 'asc' : 'desc';
            $sortLabel = $currentSort === 'desc' ? 'Urutkan: Terbaru' : 'Urutkan: Lama';
        @endphp
        <a href="{{ request()->fullUrlWithQuery(['sort' => $nextSort]) }}" class="btn btn-outline-primary ms-2">
            {{ $sortLabel }}
        </a>
    </div>

    {{-- Tabel Peserta --}}
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Peserta</th>
                        <th>No HP</th>
                        <th>Instansi/Sekolah</th>
                        <th>Jurusan</th>
                        <th>Lokasi Magang</th>
                        <th>Periode Magang</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peserta as $p)
                    <tr>
                        <td>{{ $p->id }}</td>
                        <td>
                            <strong>{{ $p->pengajuan->nama_pemohon }}</strong>
                            @if($p->pengajuan->nama_anggota)
                                <br><small class="text-muted">
                                    Anggota: {{ $p->pengajuan->nama_anggota }}
                                </small>
                            @endif
                        </td>
                        <td>{{ $p->pengajuan->no_hp }}</td>
                        <td>{{ $p->instansi_sekolah_universitas }}</td>
                        <td>{{ $p->jurusan }}</td>
                        <td>
                            {{ optional($p->lokasi)->bidang ?? '-' }}
                            <br><small>{{ optional($p->lokasi)->tim ?? '' }}</small>
                        </td>
                        <td>
                            <small>
                                <strong>Mulai:</strong> {{ \Carbon\Carbon::parse($p->mulai_magang)->format('d/m/Y') }}<br>
                                <strong>Selesai:</strong> {{ \Carbon\Carbon::parse($p->selesai_magang)->format('d/m/Y') }}
                            </small>
                        </td>
                        <td>
                            @php
                                $now = \Carbon\Carbon::now();
                                $mulai = \Carbon\Carbon::parse($p->mulai_magang);
                                $selesai = \Carbon\Carbon::parse($p->selesai_magang);
                            @endphp
                            
                            @if($now->lt($mulai))
                                <span class="badge bg-warning">Belum Mulai</span>
                            @elseif($now->between($mulai, $selesai))
                                <span class="badge bg-success">Sedang Magang</span>
                            @else
                                <span class="badge bg-info">Selesai</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.penerimaan.show', $p->id) }}" 
                                   class="btn btn-info btn-sm" title="Lihat Detail">
                                    <i class="mdi mdi-eye"></i>
                                </a>
                                <a href="{{ route('admin.penerimaan.edit', $p->id) }}" 
                                   class="btn btn-warning btn-sm" title="Edit Data">
                                    <i class="mdi mdi-pencil"></i>
                                </a>
                                @if($p->file_surat)
                                    <a href="{{ Storage::url($p->file_surat) }}" 
                                       target="_blank" class="btn btn-success btn-sm" title="Lihat Surat">
                                        <i class="mdi mdi-file-document"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">
                            <i class="mdi mdi-account-off mdi-48px d-block mb-2"></i>
                            Belum ada peserta magang yang terdaftar
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination jika diperlukan --}}
    @if(method_exists($peserta, 'links'))
        <div class="d-flex justify-content-center mt-4">
            {{ $peserta->links() }}
        </div>
    @endif
</div>
@endsection
