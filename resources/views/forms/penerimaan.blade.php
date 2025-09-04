<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Penerimaan Magang</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        
        #auth {
            min-height: 100vh;
        }
        
        .auth-header {
            background: linear-gradient(135deg, #385096 0%, #4a6bdf 100%);
            position: sticky;
            top: 0;
            z-index: 1000;
            overflow: hidden;
            padding: 24px 0;
            text-align: center;
        }
        
        .auth-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        
        .auth-logo {
            position: relative;
            z-index: 2;
            margin-bottom: 20px;
        }
        
        .auth-logo img {
            width: 80px;
            height: 80px;
            object-fit: contain;
            filter: brightness(0) invert(1);
        }
        
        .auth-title, .auth-subtitle { display: none !important; }
        
        .form-container {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin: 20px auto;
            max-width: 700px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-label {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
            display: block;
            font-size: 0.95rem;
        }
        
        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 15px 18px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8fafc;
        }
        
        .form-control:focus {
            border-color: #385096;
            box-shadow: 0 0 0 3px rgba(56, 80, 150, 0.1);
            background: white;
            outline: none;
        }
        
        .form-control::placeholder {
            color: #a0aec0;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #385096 0%, #4a6bdf 100%);
            border: none;
            border-radius: 12px;
            padding: 16px 32px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(56, 80, 150, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(56, 80, 150, 0.4);
        }
        
        .btn-outline-primary {
            border: 2px solid #385096;
            color: #385096;
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-outline-primary:hover {
            background: #385096;
            color: white;
            transform: translateY(-1px);
        }
        
        .btn-outline-danger {
            border: 2px solid #e53e3e;
            color: #e53e3e;
            border-radius: 8px;
            padding: 8px 12px;
            transition: all 0.3s ease;
        }
        
        .btn-outline-danger:hover {
            background: #e53e3e;
            color: white;
        }
        
        .peserta-item {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }
        
        .peserta-item:hover {
            border-color: #385096;
            box-shadow: 0 2px 8px rgba(56, 80, 150, 0.1);
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            padding: 16px 20px;
            margin-bottom: 25px;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
            color: white;
        }
        
        .alert ul {
            margin: 0;
            padding-left: 20px;
        }
        
        .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 15px 18px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8fafc;
        }
        
        .form-select:focus {
            border-color: #385096;
            box-shadow: 0 0 0 3px rgba(56, 80, 150, 0.1);
            background: white;
        }
        
        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }
        
        .file-upload {
            border: 2px dashed #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
            background: #f8fafc;
        }
        
        .file-upload:hover {
            border-color: #385096;
            background: #f1f5f9;
        }
        
        .file-upload input[type="file"] {
            display: none;
        }
        
        .file-upload-label {
            cursor: pointer;
            color: #385096;
            font-weight: 500;
        }
        
        .file-info {
            margin-top: 10px;
            font-size: 0.9rem;
            color: #64748b;
        }
        
        .form-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid #e2e8f0;
        }
        
        .form-footer a {
            color: #385096;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .form-footer a:hover {
            color: #4a6bdf;
        }
        
        @media (max-width: 768px) {
            .form-container {
                margin: 10px;
                padding: 20px;
            }
            
            .auth-header {
                padding: 18px 0;
            }
        }
    </style>
</head>

<body>
    <div id="auth">
        <!-- Header Section -->
        <div class="auth-header">
            <div class="container">
                <div class="auth-logo">
                    <img src="{{ asset('images/logo/logo.png') }}" alt="Logo">
                </div>
                <h3 class="text-white mb-0">Form Penerimaan Magang</h3>
            </div>
        </div>

        <!-- Form Section -->
        <div class="container">
            <div class="d-flex align-items-center justify-content-center">
                <div class="form-container">
                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('penerimaan.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="pengajuan_id" value="{{ $pengajuan->id }}">

                        <!-- Peserta Magang -->
                        <div class="form-group">
                            <label class="form-label">Nama dan Telepon Peserta Magang</label>
                            <div id="peserta-container">
                                <div class="peserta-item">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" name="peserta_nama[]" class="form-control only-letters" placeholder="Nama Peserta" required pattern="^[A-Za-zÀ-ÿ\s]+$" title="Hanya huruf dan spasi" value="{{ $pengajuan->nama_pemohon }}">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="peserta_telepon[]" class="form-control only-digits" placeholder="No Telepon" required pattern="^\d{9,15}$" title="Nomor HP harus 9-15 digit" value="{{ $pengajuan->no_hp }}">
                                        </div>
                                    </div>
                                </div>
                                @if($pengajuan->anggota)
                                    @foreach(json_decode($pengajuan->anggota, true) as $anggota)
                                    <div class="peserta-item">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="text" name="peserta_nama[]" class="form-control only-letters" placeholder="Nama Peserta" required pattern="^[A-Za-zÀ-ÿ\s]+$" title="Hanya huruf dan spasi" value="{{ $anggota['nama'] }}">
                                            </div>
                                            <div class="col-md-5">
                                                <input type="text" name="peserta_telepon[]" class="form-control only-digits" placeholder="No Telepon" required pattern="^\d{9,15}$" title="Nomor HP harus 9-15 digit" value="{{ $anggota['telepon'] }}">
                                            </div>
                                            <div class="col-md-1 d-grid">
                                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removePeserta(this)" title="Hapus peserta">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                            <button type="button" class="btn btn-outline-primary" onclick="addPeserta()">
                                <i class="bi bi-plus-circle me-2"></i>Tambah Peserta
                            </button>
                        </div>

                        <!-- Instansi/Sekolah/Universitas -->
                        <div class="form-group">
                            <label class="form-label">Instansi/Sekolah/Universitas</label>
                            <input type="text" name="instansi" class="form-control" placeholder="Nama instansi" required value="{{ $pengajuan->asal_instansi }}">
                        </div>

                        <!-- Jurusan -->
                        <div class="form-group">
                            <label class="form-label">Jurusan</label>
                            <input type="text" name="jurusan" class="form-control" placeholder="Nama jurusan" required value="{{ $pengajuan->jurusan }}">
                        </div>

                        <!-- Magang pada Tim -->
                        <div class="form-group">
                            <label class="form-label">Magang pada Tim (sesuai master lokasi)</label>
                            <select name="lokasi_id" class="form-select" required>
                                <option value="">-- Pilih Tim --</option>
                                @foreach($lokasi as $item)
                                    <option value="{{ $item->id }}" {{ $pengajuan->lokasi_id == $item->id ? 'selected' : '' }}>
                                        {{ $item->bidang }} - {{ $item->tim }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tanggal Magang -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Mulai Magang</label>
                                    <input type="date" name="mulai_magang" class="form-control" required value="{{ $pengajuan->mulai_magang }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Selesai Magang</label>
                                    <input type="date" name="selesai_magang" class="form-control" required value="{{ $pengajuan->selesai_magang }}">
                                </div>
                            </div>
                        </div>

                        <!-- Upload Surat Pengantar -->
                        <div class="form-group">
                            <label class="form-label">Upload Surat Pengantar / Izin Magang dari Instansi</label>
                            <div class="file-upload">
                                <label for="surat_pengantar" class="file-upload-label">
                                    <i class="bi bi-cloud-upload fs-3 d-block mb-2"></i>
                                    <span>Klik untuk upload surat pengantar</span>
                                    <div class="file-info">Format: PDF, Maksimal 2MB</div>
                                </label>
                                <input type="file" id="surat_pengantar" name="surat_pengantar" accept=".pdf" required>
                                <div id="surat_pengantar_info" class="mt-2"></div>
                            </div>
                        </div>

                        <!-- Upload Proposal Magang -->
                        <div class="form-group">
                            <label class="form-label">Proposal Magang</label>
                            <div class="file-upload">
                                <label for="proposal_magang" class="file-upload-label">
                                    <i class="bi bi-cloud-upload fs-3 d-block mb-2"></i>
                                    <span>Klik untuk upload proposal magang</span>
                                    <div class="file-info">Format: PDF, Maksimal 5MB</div>
                                </label>
                                <input type="file" id="proposal_magang" name="proposal_magang" accept=".pdf" required>
                                <div id="proposal_magang_info" class="mt-2"></div>
                            </div>
                        </div>

                        <!-- Upload KTP Peserta -->
                        <div class="form-group">
                            <label class="form-label">KTP Peserta Magang</label>
                            <div class="file-upload">
                                <label for="ktp_peserta" class="file-upload-label">
                                    <i class="bi bi-cloud-upload fs-3 d-block mb-2"></i>
                                    <span>Klik untuk upload KTP peserta</span>
                                    <div class="file-info">Format: PDF, Maksimal 5MB</div>
                                </label>
                                <input type="file" id="ktp_peserta" name="ktp_peserta" accept=".pdf" required>
                                <div id="ktp_peserta_info" class="mt-2"></div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-send me-2"></i>Kirim Form Penerimaan
                            </button>
                        </div>
                    </form>

                    <div class="form-footer">
                        <a href="{{ route('login') }}">
                            <i class="bi bi-arrow-left me-2"></i>Kembali ke Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function addPeserta() {
            const container = document.getElementById('peserta-container');
            const newItem = document.createElement('div');
            newItem.className = 'peserta-item';
            newItem.innerHTML = `
                <div class="row align-items-center g-2">
                    <div class="col-md-6">
                        <input type="text" name="peserta_nama[]" class="form-control only-letters" placeholder="Nama Peserta" required pattern="^[A-Za-zÀ-ÿ\\s]+$" title="Hanya huruf dan spasi">
                    </div>
                    <div class="col-md-5">
                        <input type="text" name="peserta_telepon[]" class="form-control only-digits" placeholder="No Telepon" required pattern="^\\d{9,15}$" title="Nomor HP harus 9-15 digit">
                    </div>
                    <div class="col-md-1 d-grid">
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removePeserta(this)" title="Hapus peserta">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(newItem);
        }

        function removePeserta(button) {
            button.closest('.peserta-item').remove();
        }

        // File upload handlers
        document.getElementById('surat_pengantar').addEventListener('change', function(e) {
            handleFileUpload(e, 'surat_pengantar_info', 2);
        });

        document.getElementById('proposal_magang').addEventListener('change', function(e) {
            handleFileUpload(e, 'proposal_magang_info', 5);
        });

        document.getElementById('ktp_peserta').addEventListener('change', function(e) {
            handleFileUpload(e, 'ktp_peserta_info', 5);
        });

        function handleFileUpload(event, infoId, maxSizeMB) {
            const file = event.target.files[0];
            const infoDiv = document.getElementById(infoId);
            
            if (file) {
                const fileSizeMB = file.size / (1024 * 1024);
                
                if (fileSizeMB > maxSizeMB) {
                    infoDiv.innerHTML = `<span class="text-danger"><i class="bi bi-exclamation-triangle"></i> File terlalu besar! Maksimal ${maxSizeMB}MB</span>`;
                    event.target.value = '';
                } else if (file.type !== 'application/pdf') {
                    infoDiv.innerHTML = `<span class="text-danger"><i class="bi bi-exclamation-triangle"></i> Hanya file PDF yang diizinkan</span>`;
                    event.target.value = '';
                } else {
                    infoDiv.innerHTML = `<span class="text-success"><i class="bi bi-check-circle"></i> ${file.name} (${fileSizeMB.toFixed(2)}MB)</span>`;
                }
            }
        }

        // Input sanitization
        document.addEventListener('input', function(e){
            if(e.target.classList && e.target.classList.contains('only-letters')){
                e.target.value = e.target.value.replace(/[^A-Za-zÀ-ÿ\s]/g,'');
            }
            if(e.target.classList && e.target.classList.contains('only-digits')){
                e.target.value = e.target.value.replace(/[^\d]/g,'');
            }
        });
    </script>
</body>

</html>
