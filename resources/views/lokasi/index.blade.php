@extends('layout.app')
@section('title', 'Lokasi Magang')
@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <a href="{{ route('admin.master.lokasi.create') }}" class="btn btn-primary mb-3">Tambah Lokasi</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Bidang</th>
                <th>Tim</th>
                <th>Quota</th>
                <th>Alamat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lokasi as $l)
            <tr>
                <td>{{ $l->id }}</td>
                <td>{{ $l->bidang }}</td>
                <td>{{ $l->tim }}</td>
                <td>{{ $l->quota }}</td>
                <td>{{ $l->alamat }}</td>
                <td>
                    <a href="{{ route('admin.master.lokasi.edit', $l->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('admin.master.lokasi.destroy', $l->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 