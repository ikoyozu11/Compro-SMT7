@extends('layout.app')

@section('title', 'Hasil Magang')

@section('content')
<section class="section">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Upload Hasil Magang</h4>
                </div>
                <div class="card-body">
                    @if($penerimaan)
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6>Informasi Magang:</h6>
                                <p><strong>Lokasi:</strong> {{ $penerimaan->lokasi->nama_lokasi ?? 'N/A' }}</p>
                                <p><strong>Mulai Magang:</strong> {{ $penerimaan->mulai_magang ? $penerimaan->mulai_magang->format('d-m-Y') : 'N/A' }}</p>
                                <p><strong>Selesai Magang:</strong> {{ $penerimaan->selesai_magang ? $penerimaan->selesai_magang->format('d-m-Y') : 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Status Hasil Magang:</h6>
                                @if($penerimaan->hasilMagang)
                                    <span class="badge 
                                        @if($penerimaan->hasilMagang->status == 'completed') bg-success
                                        @elseif($penerimaan->hasilMagang->status == 'pending') bg-warning
                                        @else bg-secondary @endif">
                                        {{ ucfirst($penerimaan->hasilMagang->status) }}
                                    </span>
                                    <p class="mt-2"><strong>Tanggal Upload:</strong> {{ $penerimaan->hasilMagang->tanggal_selesai ? $penerimaan->hasilMagang->tanggal_selesai->format('d-m-Y H:i') : 'N/A' }}</p>
                                @else
                                    <span class="badge bg-danger">Belum Upload</span>
                                @endif
                            </div>
                        </div>

                        @if(!$penerimaan->hasilMagang)
                            <!-- Upload Laporan Hasil Magang -->
                            <div class="card">
                                <div class="card-header">
                                    <h5>Upload Laporan Hasil Magang</h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('mg.hasil.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="laporan_hasil_magang" class="form-label">Laporan Hasil Magang (PDF)</label>
                                            <input type="file" class="form-control @error('laporan_hasil_magang') is-invalid @enderror" 
                                                   id="laporan_hasil_magang" name="laporan_hasil_magang" accept=".pdf" required>
                                            <div class="form-text">Maksimal 10MB, format PDF</div>
                                            @error('laporan_hasil_magang')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="catatan" class="form-label">Catatan (Opsional)</label>
                                            <textarea class="form-control @error('catatan') is-invalid @enderror" 
                                                      id="catatan" name="catatan" rows="3" 
                                                      placeholder="Tambahkan catatan atau keterangan tambahan...">{{ old('catatan') }}</textarea>
                                            @error('catatan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-upload"></i> Upload Laporan
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <!-- Show uploaded files -->
                            <div class="card">
                                <div class="card-header">
                                    <h5>File yang Sudah Diupload</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Laporan Hasil Magang:</h6>
                                            @if($penerimaan->hasilMagang->laporan_hasil_magang)
                                                <p class="text-success">
                                                    <i class="bi bi-file-pdf"></i> 
                                                    Laporan berhasil diupload
                                                </p>
                                                <small class="text-muted">
                                                    Uploaded: {{ $penerimaan->hasilMagang->tanggal_selesai ? $penerimaan->hasilMagang->tanggal_selesai->format('d-m-Y H:i') : 'N/A' }}
                                                </small>
                                            @else
                                                <p class="text-danger">Belum diupload</p>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Surat Keterangan Selesai:</h6>
                                            @if($penerimaan->hasilMagang->surat_keterangan_selesai)
                                                <p class="text-success">
                                                    <i class="bi bi-file-pdf"></i> 
                                                    Surat keterangan berhasil diupload
                                                </p>
                                            @else
                                                <p class="text-warning">Belum diupload</p>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    @if($penerimaan->hasilMagang->catatan)
                                        <div class="mt-3">
                                            <h6>Catatan:</h6>
                                            <p class="text-muted">{{ $penerimaan->hasilMagang->catatan }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Upload Surat Keterangan if not uploaded yet -->
                            @if(!$penerimaan->hasilMagang->surat_keterangan_selesai)
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5>Upload Surat Keterangan Selesai Magang</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('mg.hasil.upload-surat') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="surat_keterangan_selesai" class="form-label">Surat Keterangan Selesai (PDF)</label>
                                                <input type="file" class="form-control @error('surat_keterangan_selesai') is-invalid @enderror" 
                                                       id="surat_keterangan_selesai" name="surat_keterangan_selesai" accept=".pdf" required>
                                                <div class="form-text">Maksimal 2MB, format PDF</div>
                                                @error('surat_keterangan_selesai')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="catatan_surat" class="form-label">Catatan (Opsional)</label>
                                                <textarea class="form-control @error('catatan') is-invalid @enderror" 
                                                          id="catatan_surat" name="catatan" rows="3" 
                                                          placeholder="Tambahkan catatan untuk surat keterangan...">{{ old('catatan') }}</textarea>
                                                @error('catatan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <button type="submit" class="btn btn-success">
                                                <i class="bi bi-upload"></i> Upload Surat Keterangan
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @else
                        <div class="alert alert-warning">
                            <h5>Belum Ada Data Penerimaan</h5>
                            <p>Anda belum memiliki data penerimaan magang. Silakan hubungi administrator untuk informasi lebih lanjut.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
