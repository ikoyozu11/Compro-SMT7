@extends('layout.app')
@section('title', 'Detail Penelitian')
@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detail Penelitian</h3>
                <p class="text-subtitle text-muted">Detail pengajuan penelitian</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.penelitian.index') }}">Daftar Penelitian</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Informasi Penelitian</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Nama:</strong></td>
                                        <td>{{ $penelitian->nama }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Instansi:</strong></td>
                                        <td>{{ $penelitian->instansi }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Jurusan:</strong></td>
                                        <td>{{ $penelitian->jurusan }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Judul Penelitian:</strong></td>
                                        <td>{{ $penelitian->judul_penelitian }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Metode:</strong></td>
                                        <td>{{ $penelitian->metode }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            <span class="{{ $penelitian->status_badge }}">
                                                {{ $penelitian->status }}
                                            </span>
                                        </td>
                                    </tr>
                                    @if($penelitian->tanggal_pelaksanaan)
                                    <tr>
                                        <td><strong>Tanggal Pelaksanaan:</strong></td>
                                        <td>{{ $penelitian->tanggal_pelaksanaan->format('d/m/Y') }}</td>
                                    </tr>
                                    @endif
                                    @if($penelitian->keterangan_penolakan)
                                    <tr>
                                        <td><strong>Keterangan Penolakan:</strong></td>
                                        <td>{{ $penelitian->keterangan_penolakan }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td><strong>Tanggal Pengajuan:</strong></td>
                                        <td>{{ $penelitian->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- File Downloads -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">File Lampiran</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="list-group">
                                    <a href="{{ route('admin.penelitian.download', ['id' => $penelitian->id, 'fileType' => 'surat_izin']) }}" 
                                       class="list-group-item list-group-item-action">
                                        <i class="bi bi-file-earmark-pdf"></i> Surat Izin
                                    </a>
                                    <a href="{{ route('admin.penelitian.download', ['id' => $penelitian->id, 'fileType' => 'proposal']) }}" 
                                       class="list-group-item list-group-item-action">
                                        <i class="bi bi-file-earmark-pdf"></i> Proposal
                                    </a>
                                    <a href="{{ route('admin.penelitian.download', ['id' => $penelitian->id, 'fileType' => 'daftar_pertanyaan']) }}" 
                                       class="list-group-item list-group-item-action">
                                        <i class="bi bi-file-earmark-pdf"></i> Daftar Pertanyaan
                                    </a>
                                    <a href="{{ route('admin.penelitian.download', ['id' => $penelitian->id, 'fileType' => 'ktp']) }}" 
                                       class="list-group-item list-group-item-action">
                                        <i class="bi bi-file-earmark-image"></i> KTP
                                    </a>
                                    @if($penelitian->surat_selesai)
                                    <a href="{{ route('admin.penelitian.download', ['id' => $penelitian->id, 'fileType' => 'surat_selesai']) }}" 
                                       class="list-group-item list-group-item-action">
                                        <i class="bi bi-file-earmark-pdf"></i> Surat Selesai
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Status Update -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Update Status</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.penelitian.update-status', $penelitian->id) }}" 
                              enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="status">Status:</label>
                                <select name="status" id="status" class="form-select" onchange="toggleFields()">
                                    <option value="Pengajuan" {{ $penelitian->status == 'Pengajuan' ? 'selected' : '' }}>Pengajuan</option>
                                    <option value="Diterima" {{ $penelitian->status == 'Diterima' ? 'selected' : '' }}>Diterima</option>
                                    <option value="Ditolak" {{ $penelitian->status == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </div>

                            <!-- Fields for Diterima status -->
                            <div id="diterimaFields" style="display: none;">
                                <div class="form-group mb-3">
                                    <label for="tanggal_pelaksanaan">Tanggal Pelaksanaan:</label>
                                    <input type="date" name="tanggal_pelaksanaan" id="tanggal_pelaksanaan" 
                                           class="form-control" value="{{ $penelitian->tanggal_pelaksanaan?->format('Y-m-d') }}">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="surat_selesai">Upload Surat Selesai:</label>
                                    <input type="file" name="surat_selesai" id="surat_selesai" 
                                           class="form-control" accept=".pdf,.doc,.docx">
                                    <small class="text-muted">Format: PDF, DOC, DOCX (Max 2MB)</small>
                                </div>
                            </div>

                            <!-- Fields for Ditolak status -->
                            <div id="ditolakFields" style="display: none;">
                                <div class="form-group mb-3">
                                    <label for="keterangan_penolakan">Keterangan Penolakan:</label>
                                    <textarea name="keterangan_penolakan" id="keterangan_penolakan" 
                                              class="form-control" rows="3">{{ $penelitian->keterangan_penolakan }}</textarea>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-save"></i> Update Status
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
function toggleFields() {
    const status = document.getElementById('status').value;
    const diterimaFields = document.getElementById('diterimaFields');
    const ditolakFields = document.getElementById('ditolakFields');
    
    // Hide all fields first
    diterimaFields.style.display = 'none';
    ditolakFields.style.display = 'none';
    
    // Show relevant fields based on status
    if (status === 'Diterima') {
        diterimaFields.style.display = 'block';
    } else if (status === 'Ditolak') {
        ditolakFields.style.display = 'block';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleFields();
});
</script>
@endsection 