@extends('layout.app')
@section('title', 'Buat Form Link Magang')
@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                
                <p class="text-subtitle text-muted">Buat form link baru untuk pengajuan magang</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.form_links.index') }}">Form Link Magang</a></li>
                        <li class="breadcrumb-item active">Buat Baru</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                <div class="card-header"></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.form_links.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label">Judul Form Magang</label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi (Opsional)</label>
                            <textarea name="description" id="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="expires_at" class="form-label">Tanggal Kadaluarsa (Opsional)</label>
                            <input type="datetime-local" name="expires_at" id="expires_at" class="form-control" value="{{ old('expires_at') }}">
                            <small class="text-muted">Biarkan kosong jika tidak ingin ada batas waktu</small>
                            @error('expires_at')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.form_links.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Buat Form Link Magang</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection 