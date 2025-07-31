@extends('layout.app')
@section('title', 'Daftar Pengajuan Penelitian')
@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Daftar Pengajuan Penelitian</h3>
                <p class="text-subtitle text-muted">Kelola semua pengajuan penelitian</p>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="card-title">Daftar Penelitian</h4>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="{{ route('admin.penelitian.link') }}" class="btn btn-primary">
                            <i class="bi bi-link-45deg"></i> Generate Link
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <form method="GET" action="{{ route('admin.penelitian.index') }}">
                            <div class="input-group">
                                <select name="status" class="form-select" onchange="this.form.submit()">
                                    <option value="">Semua Status</option>
                                    <option value="Pengajuan" {{ request('status') == 'Pengajuan' ? 'selected' : '' }}>Pengajuan</option>
                                    <option value="Diterima" {{ request('status') == 'Diterima' ? 'selected' : '' }}>Diterima</option>
                                    <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                                <button type="submit" class="btn btn-outline-secondary">Filter</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Instansi</th>
                                <th>Judul Penelitian</th>
                                <th>Status</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($penelitian as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->instansi }}</td>
                                <td>{{ $item->judul_penelitian }}</td>
                                <td>
                                    <span class="{{ $item->status_badge }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.penelitian.show', $item->id) }}" 
                                           class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                        @if($item->status === 'Diterima')
                                        <button type="button" class="btn btn-sm btn-success" 
                                                onclick="openPenjadwalanModal({{ $item->id }})">
                                            <i class="bi bi-calendar"></i> Penjadwalan
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data penelitian</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Penjadwalan Modal -->
<div class="modal fade" id="penjadwalanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Penjadwalan Penelitian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="penjadwalanForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tanggal_pelaksanaan">Tanggal Pelaksanaan:</label>
                        <input type="date" class="form-control" id="tanggal_pelaksanaan" 
                               name="tanggal_pelaksanaan" required>
                    </div>
                    <div class="form-group mt-3">
                        <label for="surat_selesai">Upload Surat Selesai (Opsional):</label>
                        <input type="file" class="form-control" id="surat_selesai" 
                               name="surat_selesai" accept=".pdf,.doc,.docx">
                        <small class="text-muted">Format: PDF, DOC, DOCX (Max 2MB)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openPenjadwalanModal(id) {
    const modal = document.getElementById('penjadwalanModal');
    const form = document.getElementById('penjadwalanForm');
    form.action = `/admin/penelitian/${id}/update-status`;
    
    // Add hidden input for status
    let statusInput = form.querySelector('input[name="status"]');
    if (!statusInput) {
        statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = 'Diterima';
        form.appendChild(statusInput);
    }
    
    const modalInstance = new bootstrap.Modal(modal);
    modalInstance.show();
}
</script>
@endsection 