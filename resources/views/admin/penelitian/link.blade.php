@extends('layout.app')
@section('title', 'Link Pengajuan Penelitian')
@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Link Pengajuan Penelitian</h3>
                <p class="text-subtitle text-muted">Link untuk diberikan kepada pemohon penelitian</p>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Link Form Pengajuan Penelitian</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="link">Link Form Pengajuan:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="link" value="{{ $link }}" readonly>
                                <button class="btn btn-primary" type="button" onclick="copyLink()">
                                    <i class="bi bi-clipboard"></i> Copy
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-8">
                        <div class="alert alert-info">
                            <h6><i class="bi bi-info-circle"></i> Cara Penggunaan:</h6>
                            <ol>
                                <li>Copy link di atas</li>
                                <li>Bagikan link tersebut kepada pemohon penelitian</li>
                                <li>Pemohon dapat mengisi form pengajuan melalui link tersebut</li>
                                <li>Pengajuan akan muncul di halaman daftar penelitian</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
function copyLink() {
    const linkInput = document.getElementById('link');
    linkInput.select();
    linkInput.setSelectionRange(0, 99999);
    document.execCommand('copy');
    
    // Show success message
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="bi bi-check"></i> Copied!';
    button.classList.remove('btn-primary');
    button.classList.add('btn-success');
    
    setTimeout(() => {
        button.innerHTML = originalText;
        button.classList.remove('btn-success');
        button.classList.add('btn-primary');
    }, 2000);
}
</script>
@endsection 