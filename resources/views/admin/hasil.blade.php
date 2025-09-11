@extends('layout.app')
@section('title', 'Hasil Magang')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Hasil Magang</h4>
                        <small class="text-muted">Kelola laporan hasil magang dan surat keterangan selesai</small>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Section 1: Pending Penerimaan (Approved but no report yet) -->
                    @if($pendingPenerimaan->count() > 0)
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2">Penerimaan yang Belum Ada Laporan</h5>
                        <p class="text-muted">Upload laporan hasil magang untuk penerimaan yang sudah disetujui</p>
                        
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nama Pemohon</th>
                                        <th>Instansi</th>
                                        <th>Lokasi</th>
                                        <th>Periode</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingPenerimaan as $item)
                                    <tr>
                                        <td>
                                            @if($item->pengajuan)
                                                @php
                                                    $allNames = [$item->pengajuan->nama_pemohon];
                                                    if($item->pengajuan->nama_anggota) {
                                                        $anggotaList = explode('; ', $item->pengajuan->nama_anggota);
                                                        foreach($anggotaList as $anggotaString) {
                                                            if (preg_match('/^(.+?)\s*\(HP:\s*(.+?)\)$/', trim($anggotaString), $matches)) {
                                                                $allNames[] = trim($matches[1]);
                                                            }
                                                        }
                                                    }
                                                @endphp
                                                {{ implode(', ', $allNames) }}
                                            @else
                                                <span class="text-muted">Data tidak tersedia</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->instansi_sekolah_universitas }}</td>
                                        <td>
                                            @if($item->lokasi)
                                                {{ $item->lokasi->nama_lokasi }}
                                            @else
                                                <span class="text-muted">Data tidak tersedia</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($item->mulai_magang)->format('d/m/Y') }} - 
                                            {{ \Carbon\Carbon::parse($item->selesai_magang)->format('d/m/Y') }}
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" 
                                                    data-bs-toggle="modal" data-bs-target="#uploadLaporanModal{{ $item->id }}">
                                                <i class="mdi mdi-upload"></i> Upload Laporan
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- Section 2: Hasil Magang yang Sudah Ada -->
                    @if($hasilMagang->count() > 0)
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2">Laporan Hasil Magang</h5>
                        <p class="text-muted">Daftar laporan hasil magang yang sudah diupload</p>
                        
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nama Pemohon</th>
                                        <th>Instansi</th>
                                        <th>Lokasi</th>
                                        <th>Periode</th>
                                        <th>Status</th>
                                        <th>Tanggal Selesai</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($hasilMagang as $item)
                                    <tr>
                                        <td>
                                            @if($item->penerimaan && $item->penerimaan->pengajuan)
                                                {{ $item->penerimaan->pengajuan->nama_pemohon }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->penerimaan && $item->penerimaan->pengajuan)
                                                {{ $item->penerimaan->pengajuan->asal_instansi }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->penerimaan && $item->penerimaan->lokasi)
                                                {{ $item->penerimaan->lokasi->nama_lokasi }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->penerimaan)
                                                {{ $item->penerimaan->mulai_magang ? \Carbon\Carbon::parse($item->penerimaan->mulai_magang)->format('d/m/Y') : '-' }} - 
                                                {{ $item->penerimaan->selesai_magang ? \Carbon\Carbon::parse($item->penerimaan->selesai_magang)->format('d/m/Y') : '-' }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($item->status == 'completed')
                                                <span class="badge bg-success">Selesai</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $item->status }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->tanggal_selesai ? \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') : '-' }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @if($item->laporan_hasil_magang)
                                                    <a href="{{ route('admin.hasil.download.laporan', $item->id) }}" 
                                                       class="btn btn-sm btn-success" title="Download Laporan">
                                                        <i class="bi bi-download"></i> Laporan
                                                    </a>
                                                @endif
                                                
                                                @if($item->surat_keterangan_selesai)
                                                    <a href="{{ route('admin.hasil.download.surat', $item->id) }}" 
                                                       class="btn btn-sm btn-info" title="Download Surat Keterangan">
                                                        <i class="bi bi-download"></i> Surat
                                                    </a>
                                                @endif
                                                
                                                @if($item->status == 'pending')
                                                    <button type="button" class="btn btn-sm btn-primary" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#uploadSuratModal{{ $item->id }}"
                                                            title="Upload Surat Keterangan">
                                                        <i class="bi bi-upload"></i> Upload Surat
                                                    </button>
                                                @endif
                                                
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                        onclick="confirmDelete({{ $item->id }})"
                                                        title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    @if($pendingPenerimaan->count() == 0 && $hasilMagang->count() == 0)
                    <div class="text-center py-5">
                        <i class="mdi mdi-file-document-outline" style="font-size: 4rem; color: #ccc;"></i>
                        <h5 class="mt-3 text-muted">Belum ada data hasil magang</h5>
                        <p class="text-muted">Data akan muncul setelah ada penerimaan yang disetujui</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upload Laporan Modal -->
@foreach($pendingPenerimaan as $item)
<div class="modal fade" id="uploadLaporanModal{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Laporan Hasil Magang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.hasil.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Pemohon</label>
                        @php
                            $allNames = [];
                            if($item->pengajuan) {
                                $allNames[] = $item->pengajuan->nama_pemohon;
                                if($item->pengajuan->nama_anggota) {
                                    $anggotaList = explode('; ', $item->pengajuan->nama_anggota);
                                    foreach($anggotaList as $anggotaString) {
                                        if (preg_match('/^(.+?)\s*\(HP:\s*(.+?)\)$/', trim($anggotaString), $matches)) {
                                            $allNames[] = trim($matches[1]);
                                        }
                                    }
                                }
                            }
                        @endphp
                        <input type="text" class="form-control" value="{{ count($allNames) > 0 ? implode(', ', $allNames) : 'Data tidak tersedia' }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Instansi</label>
                        <input type="text" class="form-control" value="{{ $item->instansi_sekolah_universitas }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="laporan_hasil_magang" class="form-label">Laporan Hasil Magang <span class="text-danger">*</span></label>
                        <input type="file" class="form-control @error('laporan_hasil_magang') is-invalid @enderror" 
                               id="laporan_hasil_magang" name="laporan_hasil_magang" 
                               accept=".pdf" required>
                        <small class="form-text text-muted">PDF, maksimal 10MB</small>
                        @error('laporan_hasil_magang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="catatan" class="form-label">Catatan</label>
                        <textarea class="form-control" id="catatan" name="catatan" rows="3" 
                                  placeholder="Catatan tambahan (opsional)"></textarea>
                    </div>
                    <input type="hidden" name="penerimaan_id" value="{{ $item->id }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Upload Laporan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- Upload Surat Keterangan Modal -->
@foreach($hasilMagang as $item)
@if(!$item->surat_keterangan_selesai)
<div class="modal fade" id="uploadSuratModal{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Surat Keterangan Selesai Magang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.hasil.upload-surat-keterangan', $item->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Pemohon</label>
                        @php
                            $allNames = [];
                            if($item->penerimaan && $item->penerimaan->pengajuan) {
                                $allNames[] = $item->penerimaan->pengajuan->nama_pemohon;
                                if($item->penerimaan->pengajuan->nama_anggota) {
                                    $anggotaList = explode('; ', $item->penerimaan->pengajuan->nama_anggota);
                                    foreach($anggotaList as $anggotaString) {
                                        if (preg_match('/^(.+?)\s*\(HP:\s*(.+?)\)$/', trim($anggotaString), $matches)) {
                                            $allNames[] = trim($matches[1]);
                                        }
                                    }
                                }
                            }
                        @endphp
                        <input type="text" class="form-control" 
                               value="{{ count($allNames) > 0 ? implode(', ', $allNames) : 'Data tidak tersedia' }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="surat_keterangan_selesai" class="form-label">Surat Keterangan Selesai Magang <span class="text-danger">*</span></label>
                        <input type="file" class="form-control @error('surat_keterangan_selesai') is-invalid @enderror" 
                               id="surat_keterangan_selesai" name="surat_keterangan_selesai" 
                               accept=".pdf" required>
                        <small class="form-text text-muted">PDF, maksimal 2MB</small>
                        @error('surat_keterangan_selesai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="catatan" class="form-label">Catatan</label>
                        <textarea class="form-control" id="catatan" name="catatan" rows="3" 
                                  placeholder="Catatan tambahan (opsional)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Upload Surat Keterangan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endforeach

<script>
// Initialize Bootstrap tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipTriggerList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection 