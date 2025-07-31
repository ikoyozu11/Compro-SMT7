<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pengajuan Penelitian</title>

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
                <div id="auth-left" class="w-100 px-4 py-5">
                    <div class="auth-logo text-center mb-4">
                        <a href="#"><img src="{{ asset('images/logo/logo.png') }}" alt="Logo"></a>
                    </div>
                    <h1 class="auth-title text-center">Form Pengajuan Penelitian</h1>
                    <p class="auth-subtitle text-center mb-4">Isi data di bawah dengan lengkap dan benar.</p>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('pengajuan.penelitian.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group mb-3">
                            <label>Nama Peneliti</label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label>Instansi</label>
                            <input type="text" name="instansi" class="form-control" value="{{ old('instansi') }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label>Jurusan</label>
                            <input type="text" name="jurusan" class="form-control" value="{{ old('jurusan') }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label>Judul Penelitian</label>
                            <textarea name="judul_penelitian" class="form-control" rows="3" required>{{ old('judul_penelitian') }}</textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label>Metode Penelitian</label>
                            <select name="metode" class="form-control" required>
                                <option value="">-- Pilih Metode --</option>
                                <option value="Kuesioner" {{ old('metode') == 'Kuesioner' ? 'selected' : '' }}>Kuesioner</option>
                                <option value="Wawancara" {{ old('metode') == 'Wawancara' ? 'selected' : '' }}>Wawancara</option>
                                <option value="Lainnya" {{ old('metode') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label>Surat Izin Penelitian (PDF, Max 2MB)</label>
                            <input type="file" name="surat_izin" class="form-control" accept=".pdf" required>
                            <small class="text-muted">Format: PDF (Max 2MB)</small>
                        </div>

                        <div class="form-group mb-3">
                            <label>Proposal Penelitian (PDF, Max 5MB)</label>
                            <input type="file" name="proposal" class="form-control" accept=".pdf" required>
                            <small class="text-muted">Format: PDF (Max 5MB)</small>
                        </div>

                        <div class="form-group mb-3">
                            <label>Daftar Pertanyaan (PDF, Max 2MB)</label>
                            <input type="file" name="daftar_pertanyaan" class="form-control" accept=".pdf" required>
                            <small class="text-muted">Format: PDF (Max 2MB)</small>
                        </div>

                        <div class="form-group mb-4">
                            <label>KTP (PDF/JPG, Max 1MB)</label>
                            <input type="file" name="ktp" class="form-control" accept=".pdf,.jpg,.jpeg" required>
                            <small class="text-muted">Format: PDF, JPG, JPEG (Max 1MB)</small>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg">Ajukan Penelitian</button>

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

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html> 