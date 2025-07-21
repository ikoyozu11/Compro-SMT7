<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pengajuan Magang</title>

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div id="auth">
        <div class="row h-100">

            <!-- Background biru kiri -->
            <div class="col-lg-3 d-none d-lg-block" style="background-color: #385096;"></div>

            <!-- Form Tengah -->
<div class="col-lg-6 col-12 d-flex align-items-center justify-content-center">
    <div id="auth-left" class="w-100 px-4 py-5"> {{-- Tambahan class py-5 di sini --}}
        <div class="auth-logo text-center mb-4">
            <a href="#"><img src="{{ asset('images/logo/logo.png') }}" alt="Logo"></a>
        </div>
        <h1 class="auth-title text-center">Form Pengajuan Magang</h1>
        <p class="auth-subtitle text-center mb-4">Isi data di bawah dengan lengkap dan benar.</p>

        <form method="POST" action="{{ route('pengajuan.store') }}">
            @csrf

            <div class="form-group mb-3">
                <label>Nama Pemohon</label>
                <input type="text" name="nama_pemohon" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label>No HP Pemohon</label>
                <input type="text" name="no_hp" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label>Nama Anggota Kelompok (pisahkan dengan ;)</label>
                <textarea name="nama_anggota" class="form-control" rows="3" required></textarea>
            </div>

            <div class="form-group mb-3">
                <label>Asal Instansi</label>
                <input type="text" name="asal_instansi" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label>Jurusan</label>
                <input type="text" name="jurusan" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label>Keahlian yang Dipelajari</label>
                <textarea name="keahlian" class="form-control" rows="3" required></textarea>
            </div>

            <div class="form-group mb-3">
                <label>Lokasi Magang</label>
                <select name="lokasi_id" class="form-control" required>
                    @foreach($lokasi as $item)
                        <option value="{{ $item->id }}">{{ $item->nama_lokasi }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label>Mulai Magang</label>
                <input type="date" name="mulai_magang" class="form-control" required>
            </div>

            <div class="form-group mb-4">
                <label>Selesai Magang</label>
                <input type="date" name="selesai_magang" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg">Ajukan</button>

            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="btn btn-link">Kembali ke Login</a>
            </div>
        </form>
    </div>
</div>


            <!-- Background biru kanan -->
            <div class="col-lg-3 d-none d-lg-block" style="background-color: #385096;"></div>
        </div>
    </div>
</body>

</html>
