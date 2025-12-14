@extends('layout.app')
@section('title', 'Daftar Pengajuan Magang')
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
</style>
<div class="container">
    <p class="text-muted mb-4">Kelola status pengajuan magang - aplikasi akan tetap di halaman ini sampai status 'Diterima'</p>
    

    {{-- Card Kuota Lokasi --}}
    <div class="row mb-4">
        @foreach($kuota as $k)
        <div class="col-md-3 mb-2">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title mb-1">{{ $k['bidang'] }}<br><small>{{ $k['tim'] }}</small></h6>
                    <span class="badge bg-primary">{{ $k['terisi'] }} / {{ $k['quota'] }} terisi</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
        <div class="d-flex justify-content-between align-items-center mb-3" >
    {{-- Dropdown Filter Status --}}
    <form method="GET" action="{{ route('admin.pengajuan.daftar') }}" id="filterForm">
        <div class="d-flex align-items-center">
            <select name="status" id="status" class="form-select me-2" onchange="this.form.submit()">
                <option value="diproses" {{ $status=='diproses'?'selected':'' }}>Diproses</option>
                <option value="ditolak" {{ $status=='ditolak'?'selected':'' }}>Ditolak</option>
                <option value="all" {{ $status=='all'?'selected':'' }}>Semua</option>
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
    {{-- Tabel Pengajuan --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Pemohon</th>
                <th>No HP</th>
                <th>Lokasi</th>
                <th>Status</th>
                <th>Tanggal Pengajuan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengajuan as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td>{{ $p->nama_pemohon }}</td>
                <td>{{ $p->no_hp }}</td>
                <td>{{ optional($p->lokasi)->bidang ?? '-' }}<br><small>{{ optional($p->lokasi)->tim ?? '' }}</small></td>
                <td>
                    @if($p->status == 'pengajuan')
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal{{ $p->id }}">Pengajuan</button>
                    @elseif($p->status == 'diproses')
                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal{{ $p->id }}">Diproses</button>
                    @elseif($p->status == 'diterima')
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal{{ $p->id }}">Diterima</button>
                    @elseif($p->status == 'ditolak')
                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal{{ $p->id }}">Ditolak</button>
                    @else
                        <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal{{ $p->id }}">{{ ucfirst($p->status) }}</button>
                    @endif
                </td>
                <td>
                    {{ $p->created_at }}
                    @if($p->status == 'ditolak' && $p->catatan)
                        <br><small class="text-danger">Alasan: {{ $p->catatan }}</small>
                    @endif
                </td>
                <td>
                    @if($p->status == 'diterima')
                        <div class="d-flex gap-1">
                            <span class="text-success">✓ Diterima</span>
                            @php
                                $existingPenerimaan = \App\Models\Penerimaan::where('pengajuan_id', $p->id)->first();
                            @endphp
                            @if($existingPenerimaan)
                                <a href="{{ route('admin.penerimaan.show', $existingPenerimaan->id) }}" 
                                   class="btn btn-info btn-sm" title="Lihat Penerimaan">
                                    <i class="mdi mdi-eye"></i> Lihat Penerimaan
                                </a>
                            @else
                                <span class="text-muted">Penerimaan sedang diproses...</span>
                            @endif
                        </div>
                    @else
                        <div class="d-flex gap-1">
                            @if($p->status == 'diproses')
                                <button class="btn btn-success btn-sm" onclick="generatePenerimaanLink({{ $p->id }})">
                                    <i class="bi bi-link-45deg"></i> Generate Link
                                </button>
                            @endif
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalStatus{{ $p->id }}">
                                @if($p->status == 'pengajuan')
                                    Proses Pengajuan
                                @elseif($p->status == 'diproses')
                                    Ubah Status
                                @elseif($p->status == 'ditolak')
                                    Review Ulang
                                @else
                                    Ubah Status
                                @endif
                            </button>
                        </div>
                    @endif
                    <!-- Modal ubah status -->
                    <div class="modal fade" id="modalStatus{{ $p->id }}" tabindex="-1" aria-labelledby="modalStatusLabel{{ $p->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('admin.pengajuan.ubah-status', $p->id) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalStatusLabel{{ $p->id }}">Ubah Status Pengajuan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-2">
                                            <label>Status</label>
                                            <select name="status" class="form-control" required onchange="toggleAlasan(this, {{ $p->id }})">
                                                <option value="diproses" {{ $p->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                                <option value="ditolak" {{ $p->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                            </select>
                                        </div>
                                        <div class="mb-2" id="alasanField{{ $p->id }}" style="display:none;">
                                            <label>Alasan Penolakan</label>
                                            <textarea name="catatan" class="form-control"></textarea>
                                        </div>
                                        <!-- Surat Penerimaan field removed - will be added later in Penerimaan page -->
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Modal Detail Pengajuan -->
                    <div class="modal fade" id="detailModal{{ $p->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $p->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="detailModalLabel{{ $p->id }}">Detail Pengajuan Magang - {{ $p->nama_pemohon }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6><strong>Informasi Pemohon</strong></h6>
                                            <p><strong>Nama:</strong> {{ $p->nama_pemohon }}</p>
                                            <p><strong>No HP:</strong> {{ $p->no_hp }}</p>
                                            <p><strong>Asal Instansi:</strong> {{ $p->asal_instansi }}</p>
                                            <p><strong>Jurusan:</strong> {{ $p->jurusan }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6><strong>Detail Magang</strong></h6>
                                            <p><strong>Lokasi:</strong> {{ optional($p->lokasi)->bidang ?? '-' }} - {{ optional($p->lokasi)->tim ?? '' }}</p>
                                            <p><strong>Mulai:</strong> {{ $p->mulai_magang }}</p>
                                            <p><strong>Selesai:</strong> {{ $p->selesai_magang }}</p>
                                            <p><strong>Status:</strong> 
                                                @if($p->status == 'pengajuan')
                                                    <span class="badge bg-warning">Pengajuan</span>
                                                @elseif($p->status == 'diproses')
                                                    <span class="badge bg-info">Diproses</span>
                                                @elseif($p->status == 'diterima')
                                                    <span class="badge bg-success">Diterima</span>
                                                @elseif($p->status == 'ditolak')
                                                    <span class="badge bg-danger">Ditolak</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <hr>
                                    
                                    <div class="row">
                                        <div class="col-12">
                                            <h6><strong>Keahlian yang Dipelajari</strong></h6>
                                            <p>{{ $p->keahlian }}</p>
                                        </div>
                                    </div>
                                    
                                    @if($p->anggota)
                                        <hr>
                                        <div class="row">
                                            <div class="col-12">
                                                <h6><strong>Anggota Kelompok</strong></h6>
                                                @php
                                                    $anggotaData = is_string($p->anggota) ? json_decode($p->anggota, true) : $p->anggota;
                                                @endphp
                                                @if(is_array($anggotaData))
                                                    @foreach($anggotaData as $index => $anggota)
                                                        <p><strong>{{ $index + 1 }}.</strong> {{ $anggota['nama'] ?? '' }} - {{ $anggota['telepon'] ?? '' }}</p>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if($p->status == 'ditolak' && $p->catatan)
                                        <hr>
                                        <div class="row">
                                            <div class="col-12">
                                                <h6><strong>Alasan Penolakan</strong></h6>
                                                <div class="alert alert-danger">{{ $p->catatan }}</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <script>
                function toggleAlasan(select, id) {
                    let alasan = document.getElementById('alasanField'+id);
                    
                    // Hide all fields first
                    alasan.style.display = 'none';
                    alasan.querySelector('textarea').required = false;
                    
                    if(select.value === 'ditolak') {
                        alasan.style.display = 'block';
                        alasan.querySelector('textarea').required = true;
                    }
                }
            </script>
            @endforeach
        </tbody>
    </table>
</div>

<script>
function generatePenerimaanLink(pengajuanId) {
    if(confirm('Generate link penerimaan untuk pengajuan ini?')) {
        fetch(`/admin/pengajuan/${pengajuanId}/generate-link`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                // Show link in a modal or copy to clipboard
                navigator.clipboard.writeText(data.link).then(() => {
                    alert('Link berhasil di-generate dan disalin ke clipboard:\n' + data.link);
                });
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat generate link');
        });
    }
}
</script>
@endsection