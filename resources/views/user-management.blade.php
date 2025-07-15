@extends('layout.app')

@section('title', 'Pengaturan Pengguna')

@section('content')

    <!-- Display success message -->
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <!-- Display Validation Errors -->
    @if($errors->any())
        <div class="alert alert-danger">
            Gagal menambahkan pengguna.<br/>Lihat pesan kesalahan pada form penambahan pengguna.
        </div>
    @endif     

    <section>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Daftar Pengguna</h4>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="user-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Status</th>
                            <th>Role</th>
                            @if(Auth::user()->role === 'admin')
                            <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @for ($i = 0; $i < count($users); $i++)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>{{ $users[$i]->name }}</td>
                            <td>
                                <span class="badge {{ $users[$i]->status == 1 ? 'bg-success' : 'bg-danger' }} ">
                                    {{ $users[$i]->status == 1 ? 'Aktif' : 'Inaktif' }}
                                </span>
                            </td>
                            <td>
                                @if(strtolower($users[$i]->role) == 'admin')
                                    <span class="badge bg-danger">Admin</span>
                                @else
                                    <span class="badge bg-success">Magang</span>
                                @endif
                            </td>
                            @if(Auth::user()->role === 'admin')
                            <td>
                                <a href="{{ route('be.um.edit.page', $users[$i]->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form method="POST" action="{{ route('be.um.delete', $users[$i]->id) }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm" {{ $users[$i]->status == 1 ? 'disabled' : '' }} onclick="return confirm('Yakin ingin menghapus pengguna ini?')">Delete</button>
                                </form>
                            </td>
                            @endif
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>

        @if(Auth::user()->role === 'admin')
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Tambah Pengguna</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form class="form form-horizontal" method="post" action="{{ route('be.um.add') }}">
                        @csrf
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <label>Username</label>
                                </div>
                                <div class="col-md-10 form-group">
                                    <input type="text" id="username" class="form-control"
                                    name="username" placeholder="Username">
                                    @error('username')
                                        <div style="color: red;">* {{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <label>Password</label>
                                </div>
                                <div class="col-md-10 form-group">
                                    <input type="text" id="password" class="form-control"
                                    name="password" placeholder="Password">
                                    @error('password')
                                        <div style="color: red;">* {{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <label>Nama</label>
                                </div>
                                <div class="col-md-10 form-group">
                                    <input type="text" id="name" class="form-control"
                                    name="name" placeholder="Nama">
                                    @error('name')
                                        <div style="color: red;">* {{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <label>Tgl Lahir</label>
                                </div>
                                <div class="col-md-10 form-group">
                                    <input type="date" id="date-birth" class="form-control"
                                    name="date-birth" placeholder="Tgl Lahir">
                                </div>
                                <div class="col-md-2">
                                    <label>Alamat</label>
                                </div>
                                <div class="col-md-10 form-group">
                                    <input type="text" id="address" class="form-control"
                                    name="address" placeholder="Alamat">
                                </div>
                                <div class="col-md-2">
                                    <label>Telepon</label>
                                </div>
                                <div class="col-md-10 form-group">
                                    <input type="text" id="phone" class="form-control"
                                    name="phone" placeholder="Telepon">
                                </div>
                                <div class="col-md-2">
                                    <label>Institusi</label>
                                </div>
                                <div class="col-md-10 form-group">
                                    <input type="text" id="institution" class="form-control"
                                    name="institution" placeholder="Institusi">
                                </div>
                                <div class="col-md-2">
                                    <label>Role</label>
                                </div>
                                <div class="col-md-10 form-group">
                                    <select id="role" class="form-control" name="role">
                                        <option value="magang">Magang</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                                <div class="col-sm-12 d-flex justify-content-end">
                                    <button type="submit"
                                    class="btn btn-primary me-1 mb-1">Simpan</button>
                                    <button type="reset"
                                    class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

    </section>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Remove Edit User Modal and related JS
    });
</script>
@endsection