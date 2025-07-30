<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Poppins', sans-serif;
        }
        .login-box {
            background-color: #6c757d; /* bootstrap secondary */
            color: white;
        }
    body {
        background color:rgba(108, 117, 125, 0.85); ;
        background-size: cover;
        font-family: 'Poppins', sans-serif;
    }

    .login-box {
        background-color: rgba(108, 117, 125, 0.85); /* semi transparan */
        color: white;
    }

    </style>
</head>
<body>

    <div class="container">
        <div class="row my-5 justify-content-center">
            <div class="col-md-6 text-center login-box p-4 rounded shadow">
                <img src="/loginn/img/tutwuri.png" class="mb-3" width="70" alt="logo">
                <h4 class="fw-bold">Masukkan Username Anda</h4>

                {{-- Tampilkan error jika ada --}}
                @if(session('error'))
                    <div class="alert alert-danger mt-3">{{ session('error') }}</div>
                @endif

                <form action="/login" method="POST">
                    @csrf
                    <div class="form-group mb-4 text-start">
                        <input type="text" class="form-control" placeholder="Masukkan Username"
                               name="username" id="username" required autofocus
                               @if(Cookie::has('username')) value="{{ Cookie::get('username') }}" @endif>
                    </div>

                    <div class="form-group mb-4 text-start">
                        <input type="password" class="form-control" placeholder="Masukkan Password"
                               name="password" id="password" required
                               @if(Cookie::has('password')) value="{{ Cookie::get('password') }}" @endif>
                    </div>

                    <div class="form-check mb-3 text-start">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                               @if(Cookie::has('username')) checked @endif>
                        <label class="form-check-label" for="remember">Ingat Saya</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Login</button>
                    {{-- Tambahan opsional jika mau pakai halaman registrasi --}}
                    {{-- <a href="/register" class="btn btn-danger w-100 mt-2">Sign Up</a> --}}
                </form>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    @include('sweetalert::alert')

</body>
</html>
