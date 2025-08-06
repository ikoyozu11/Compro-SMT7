@extends('layout.app')
@section('title', 'Daftar Pengajuan Magang')
@section('content')
<div class="container">
    <h2>Daftar Pengajuan Magang</h2>
    {{-- Card Kuota Lokasi --}}
    <div class="row mb-4">
        @foreach($kuota as $k)
        <div class="col-md-3 mb-2">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title mb-1">{{ $k['bidang'] }}<br><small>{{ $k['tim'] }}</small></h6>
                    <span class="badge bg-primary">{{ $k['terisi'] }} / {{ $k['quota'] }} terisi</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
        <div class="d-flex justify-content-between align-items-center mb-3" >
    {{-- Dropdown Filter Status --}}
    <div>
        <select name="status" id="status" class="form-select" onchange="this.form.submit()">
            <option value="diproses" {{ $status=='diproses'?'selected':'' }}>Diproses</option>
            <option value="ditolak" {{ $status=='ditolak'?'selected':'' }}>Ditolak</option>
            <option value="all" {{ $status=='all'?'selected':'' }}>Semua</option>
        </select>
    </div>

    {{-- Tombol Urutkan --}}
    @php
        $currentSort = request('sort', 'desc');
        $nextSort = $currentSort === 'desc' ? 'asc' : 'desc';
        $sortLabel = $currentSort === 'desc' ? 'Urutkan: Terbaru' : 'Urutkan: Lama';
    @endphp
    <a href="{{ request()->fullUrlWithQuery(['sort' => $nextSort]) }}" class="btn btn-outline-primary ms-2">
        {{ $sortLabel }}
    </a>
</div>
    {{-- Tabel Pengajuan --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Pemohon</th>
                <th>No HP</th>
                <th>Lokasi</th>
                <th>Status</th>
                <th>Tanggal Pengajuan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengajuan as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td>{{ $p->nama_pemohon }}</td>
                <td>{{ $p->no_hp }}</td>
                <td>{{ optional($p->lokasi)->bidang ?? '-' }}<br><small>{{ optional($p->lokasi)->tim ?? '' }}</small></td>
                <td>{{ ucfirst($p->status) }}</td>
                <td>{{ $p->created_at }}</td>
                <td>
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalStatus{{ $p->id }}">Ubah Status</button>
                    <!-- Modal ubah status -->
                    <div class="modal fade" id="modalStatus{{ $p->id }}" tabindex="-1" aria-labelledby="modalStatusLabel{{ $p->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('admin.penerimaan.ubah-status', $p->id) }}">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalStatusLabel{{ $p->id }}">Ubah Status Pengajuan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-2">
                                            <label>Status</label>
                                            <select name="status" class="form-control" required onchange="toggleAlasan(this, {{ $p->id }})">
                                                <option value="pengajuan">Pengajuan</option>
                                                <option value="diproses">Diproses</option>
                                                <option value="ditolak">Ditolak</option>
                                            </select>
                                        </div>
                                        <div class="mb-2" id="alasanField{{ $p->id }}" style="display:none;">
                                            <label>Alasan Penolakan</label>
                                            <textarea name="catatan" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
            <script>
                function toggleAlasan(select, id) {
                    let alasan = document.getElementById('alasanField'+id);
                    if(select.value === 'ditolak') {
                        alasan.style.display = 'block';
                    } else {
                        alasan.style.display = 'none';
                    }
                }
            </script>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 