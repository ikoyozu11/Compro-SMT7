@extends('layout.app')
@section('title', isset($lokasi) ? 'Edit Lokasi' : 'Tambah Lokasi')
@section('content')
<div class="container">
    <form method="POST" action="{{ isset($lokasi) ? route('admin.master.lokasi.update', $lokasi->id) : route('admin.master.lokasi.store') }}">
        @csrf
        @if(isset($lokasi))
            @method('PUT')
        @endif
        <div class="mb-3">
            <label for="bidang" class="form-label">Bidang</label>
            <input type="text" class="form-control @error('bidang') is-invalid @enderror" id="bidang" name="bidang" value="{{ old('bidang', $lokasi->bidang ?? '') }}" required>
            @error('bidang')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="tim" class="form-label">Tim</label>
            <input type="text" class="form-control @error('tim') is-invalid @enderror" id="tim" name="tim" value="{{ old('tim', $lokasi->tim ?? '') }}" required>
            @error('tim')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="quota" class="form-label">Quota</label>
            <input type="number" class="form-control @error('quota') is-invalid @enderror" id="quota" name="quota" value="{{ old('quota', $lokasi->quota ?? '') }}" required>
            @error('quota')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat">{{ old('alamat', $lokasi->alamat ?? '') }}</textarea>
            @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-success">{{ isset($lokasi) ? 'Update' : 'Tambah' }}</button>
        <a href="{{ route('admin.master.lokasi.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection 