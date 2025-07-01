<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Booking Meeting Room | Register</title>
    <link rel="icon" href="{{ asset('bookmeet.png') }}" type="image/x-icon">

    <!-- Favicon untuk layar retina (misalnya 32x32, 64x64) -->
    <link rel="icon" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" sizes="64x64" href="{{ asset('favicon-64x64.png') }}">

    <!-- Ikon untuk aplikasi web (Web App) -->
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <link
        href="https://fonts.googleapis.com/css?family=Karla:400,700&display=swap"
        rel="stylesheet"
    />
    <link
        rel="stylesheet"
        href="https://cdn.materialdesignicons.com/4.8.95/css/materialdesignicons.min.css"
    />
    <link
        rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
    />
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.8.95/css/materialdesignicons.min.css" />
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}" />
</head>
<body>
<main>
    <div class="container-fluid d-flex align-items-center justify-content-center" style="min-height:100vh;">
        <div class="row w-100 justify-content-center align-items-center">
            <div class="col-12 col-sm-6 login-section-wrapper mx-auto">
                <div class="brand-wrapper">
                    <img src="{{ asset('img/logo3.png') }}" alt="logo" class="logo" />
                </div>
                <div class="login-wrapper my-auto animate-fadein">
                    <h1 class="login-title">Register Your Account</h1>
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('message'))
                        <div class="alert alert-danger">{{ session('message') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <form method="POST" action="{{ route('register') }}" id="registerForm">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="mdi mdi-account"></i></span>
                                </div>
                            <input
                                id="name"
                                type="text"
                                name="name"
                                class="form-control"
                                placeholder="Enter your name"
                                value="{{ old('name') }}"
                                required
                                autocomplete="name"
                                autofocus
                            />
                            </div>
                            @error('name')
                            <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">E-Mail Address</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="mdi mdi-email"></i></span>
                                </div>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                class="form-control"
                                placeholder="Enter your email"
                                value="{{ old('email') }}"
                            />
                            </div>
                            @error('email')
                            <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="contact_number">Nomor HP</label>
                            <input type="text" 
                                class="form-control @error('contact_number') is-invalid @enderror" 
                                name="contact_number" 
                                id="contact_number" 
                                placeholder="0 diganti 62"
                                value="{{ old('contact_number') }}" required>

                            @error('contact_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group mb-4">
                            <label for="password">Password</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="mdi mdi-lock"></i></span>
                                </div>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control"
                                placeholder="Enter your password"
                                required
                                autocomplete="new-password"
                            />
                                <div class="input-group-append">
                                    <span class="input-group-text" onclick="password()" id="show-hide-password" style="cursor: pointer;"><i class="mdi mdi-eye"></i></span>
                                </div>
                            </div>
                            @error('password')
                            <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group mb-4">
                            <label for="password-confirm">Confirm Password</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="mdi mdi-lock"></i></span>
                                </div>
                            <input
                                type="password"
                                name="password_confirmation"
                                id="password-confirm"
                                class="form-control"
                                placeholder="Re-enter your password"
                                required
                                autocomplete="new-password"
                            />
                                <div class="input-group-append">
                                    <span class="input-group-text" onclick="passwordconfirm()" id="show-hide-password-confirm" style="cursor: pointer;"><i class="mdi mdi-eye"></i></span>
                                </div>
                            </div>
                            @error('password-confirm')
                            <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <button
                            name="register"
                            id="register"
                            class="btn btn-block login-btn"
                            type="submit"
                        >
                            <i class="mdi mdi-account-plus"></i> Register
                        </button>
                    </form>
                    <p class="login-wrapper-footer-text">
                        Already have an account?
                        <a href="{{ route('login') }}" style="color: #121257; text-decoration: none;"><i class="mdi mdi-login"></i> Login here</a>
                    </p>
                </div>
            </div>
            <div class="col-sm-6 px-0 d-none d-sm-block" style="background-color: #ffffff;display:flex;align-items:center;justify-content:center;">
                <img src="{{ asset('img/shaka_utama.png') }}" alt="login image" class="login-img" style="max-height:100vh;object-fit:contain;width:100%;filter:blur(0.5px);opacity:0.96;" />
            </div>
        </div>
    </div>
</main>
<style>
.animate-fadein {
    animation: fadein 1s;
}
@keyframes fadein {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
<script src="{{ asset('js/admin_dashboard.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>
</html>
