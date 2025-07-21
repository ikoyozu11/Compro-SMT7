<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Office Dinkominfo</title>

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/auth.css') }}">

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

</head>

<body>
    <div id="auth">

        <div class="row h-100">
            <div class="col-lg-5 col-12">
                <div id="auth-left">
                    <div class="auth-logo">
                        <a href="index.html"><img src="{{ asset('images/logo/logo.png') }}" alt="Logo"></a>
                    </div>
                    <h1 class="auth-title">Log in</h1>
                    <p class="auth-subtitle mb-5">Log in dengan username dan password anda.</p>

                    <!-- Display error message -->
                    @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif

                    <form method="post" action="{{ route('auth') }}">
                        @csrf

                        <div class="form-group position-relative has-icon-left mb-3">
                            @error('nama_pengguna')
                                <div style="color: red;">* {{ $message }}</div>
                            @enderror

                            <input type="text" class="form-control form-control-xl" placeholder="Username" name="nama_pengguna">
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                        </div>

                        
                        <div class="form-group position-relative has-icon-left mb-3">
                            @error('kata_sandi')
                                <div style="color: red;">* {{ $message }}</div>
                            @enderror

                            <input type="password" class="form-control form-control-xl" placeholder="Password" name="kata_sandi">
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                        </div>

                        
                        <div class="form-group position-relative mb-3" style="text-align: center;" >
                            @error('g-recaptcha-response')
                                <div style="color: red;">* {{ $message }}</div>
                            @enderror
                            <div class="g-recaptcha" style="display: flex; justify-content: center;" 
                                data-sitekey="6LfRv3cqAAAAAKGj4DN1I-rJBInMwHcOTkndyo_R"></div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-3">Log in</button>
                    </form>
                    <div class="text-center mt-5 text-lg fs-4">
                        
                        <p><a class="font-bold" href="auth-forgot-password.html">Lupa password?</a></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right">
                    <div id="auth-right">
                        <div class="d-flex justify-content-center align-items-center h-100">
                            <a href="{{ route('pengajuan.create') }}" class="btn btn-outline-light btn-lg">Ajukan Magang</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>

</html>