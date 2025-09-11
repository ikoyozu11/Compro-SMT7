@extends('layout.app')

@section('title', 'Progress Magang')

@section('content')

<section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Upload Laporan Progress</h4>
                </div>
                <div class="card-body">
                    <!-- Display success message -->
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    <!-- Display error message -->
                    @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif

                    <!-- Display validation errors -->
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('mg.progress.upload') }}" method="POST" enctype="multipart/form-data" id="progressForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Judul Laporan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="title" name="title" 
                                           value="{{ old('title') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="file">File PDF <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" id="file" name="file" 
                                           accept=".pdf" required>
                                    <small class="text-muted">Format: PDF, Maksimal 2MB</small>
                                    <div id="fileSizeError" class="text-danger mt-1" style="display: none;">
                                        File terlalu besar. Maksimal ukuran file adalah 2MB.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="description">Deskripsi</label>
                                    <textarea class="form-control" id="description" name="description" 
                                              rows="3" placeholder="Deskripsi singkat tentang laporan progress...">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="bi bi-upload"></i> Upload Laporan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.getElementById('file').addEventListener('change', function() {
        const file = this.files[0];
        const maxSize = 2 * 1024 * 1024; // 2MB in bytes
        const errorDiv = document.getElementById('fileSizeError');
        const submitBtn = document.getElementById('submitBtn');
        
        if (file && file.size > maxSize) {
            errorDiv.style.display = 'block';
            submitBtn.disabled = true;
            this.value = ''; // Clear the file input
        } else {
            errorDiv.style.display = 'none';
            submitBtn.disabled = false;
        }
    });
    
    document.getElementById('progressForm').addEventListener('submit', function(e) {
        const file = document.getElementById('file').files[0];
        const maxSize = 2 * 1024 * 1024; // 2MB in bytes
        
        if (file && file.size > maxSize) {
            e.preventDefault();
            alert('File terlalu besar. Maksimal ukuran file adalah 2MB.');
            return false;
        }
    });
    </script>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Riwayat Laporan Progress</h4>
                </div>
                <div class="card-body">
                    @if(count($progressList) > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Judul</th>
                                    <th>Deskripsi</th>
                                    <th>Tanggal Upload</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($progressList as $index => $progress)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $progress->title }}</td>
                                    <td>{{ $progress->description ?? '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($progress->created_at)->locale('id')->format('d M Y, H:i') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('mg.progress.download', $progress->id) }}" 
                                               class="btn btn-sm btn-success" title="Download">
                                                <i class="bi bi-download"></i>
                                            </a>
                                            <form action="{{ route('mg.progress.delete', $progress->id) }}" 
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="bi bi-file-earmark-text" style="font-size: 3rem; color: #ccc;"></i>
                        <p class="text-muted mt-2">Belum ada laporan progress yang diupload</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
