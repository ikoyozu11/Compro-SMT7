@extends('layout.app')
@section('title', 'Kelola Form Link Magang')
@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                
                <p class="text-subtitle text-muted">Kelola form link untuk pengajuan magang</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Form Link Magang</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Daftar Form Link Magang</h4>
                <a href="{{ route('admin.form_links.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus"></i> Buat Form Link Baru
                </a>
            </div>
            <div class="card-body">

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Deskripsi</th>
                            <th>Link</th>
                            <th>Status</th>
                            <th>Kadaluarsa</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($formLinks as $link)
                            <tr>
                                <td>{{ $link->title }}</td>
                                <td>{{ $link->description ?: '-' }}</td>
                                <td>
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-sm" value="{{ $link->full_url }}" readonly>
                                        <button class="btn btn-outline-secondary btn-sm" type="button" onclick="copyToClipboard('{{ $link->full_url }}')">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    @if($link->isActive())
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-danger">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    @if($link->expires_at)
                                        {{ $link->expires_at->format('d/m/Y H:i') }}
                                    @else
                                        <span class="text-muted">Tidak ada</span>
                                    @endif
                                </td>
                                <td>{{ $link->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ $link->full_url }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Lihat Form">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.form_links.toggle-status', $link->id) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-{{ $link->is_active ? 'warning' : 'success' }}" title="{{ $link->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                <i class="bi bi-{{ $link->is_active ? 'pause' : 'play' }}"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.form_links.destroy', $link->id) }}" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus form link ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-link-45deg display-4"></i>
                                        <p class="mt-2">Belum ada form link magang yang dibuat</p>
                                        <a href="{{ route('admin.form_links.create') }}" class="btn btn-primary btn-sm">
                                            <i class="bi bi-plus"></i> Buat Form Link Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const button = event.target.closest('button');
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="bi bi-check"></i>';
        button.classList.remove('btn-outline-secondary');
        button.classList.add('btn-success');
        
        setTimeout(() => {
            button.innerHTML = originalHTML;
            button.classList.remove('btn-success');
            button.classList.add('btn-outline-secondary');
        }, 2000);
    });
}
</script>
@endsection 