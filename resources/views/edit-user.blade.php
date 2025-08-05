@extends('layout.app')

@section('title', 'Edit Pengguna')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title" style="margin: 0; padding: 0; font-size: 1.2rem; font-weight: 700;">Edit Pengguna</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('be.um.edit', $user->id) }}">
                @csrf
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="{{ old('username', $user->username) }}" required>
                    @error('username')
                        <div style="color: red;">* {{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div style="color: red;">* {{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password (kosongkan jika tidak diubah)</label>
                    <input type="password" class="form-control" id="password" name="password" style="background-image: none !important; background: white !important; position: relative !important; background-repeat: no-repeat !important; background-position: unset !important; padding-right: 12px !important;">
                    <small class="form-text text-muted">Password minimal 6 karakter dengan huruf, angka, dan simbol</small>
                </div>
                <div class="mb-3">
                    <label for="date-birth" class="form-label">Tgl Lahir</label>
                    <input type="date" class="form-control" id="date-birth" name="date-birth" value="{{ old('birth_date', $user->birth_date) }}">
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Alamat</label>
                    <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $user->address) }}">
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Telepon</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                    <small class="form-text text-muted">Nomor telepon hanya boleh berisi angka.</small>
                    @error('phone')
                        <div style="color: red;">* {{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="institution" class="form-label">Institusi</label>
                    <input type="text" class="form-control" id="institution" name="institution" value="{{ old('institution', $user->institution) }}">
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" id="role" name="role">
                        <option value="magang" {{ old('role', $user->role) == 'magang' ? 'selected' : '' }}>Magang</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="1" {{ old('status', $user->status) == 1 ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('status', $user->status) == 0 ? 'selected' : '' }}>Inaktif</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('be.um') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Ensure password field is clean
    document.addEventListener('DOMContentLoaded', function() {
        const passwordField = document.getElementById('password');
        if (passwordField) {
            passwordField.style.backgroundImage = 'none';
            passwordField.style.background = 'white';
            passwordField.style.position = 'relative';
            passwordField.style.backgroundRepeat = 'no-repeat';
            passwordField.style.backgroundPosition = 'unset';
            passwordField.style.paddingRight = '12px';
        }
    });
</script>
@endsection