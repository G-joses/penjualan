<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="icon" href="{{ asset('logo/logo.png') }}" type="image/x-icon">
</head>

<body class="bg-light d-flex align-items-center" style="height: 100vh;">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-lg">
                    <div class="card-header text-center bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-shield-lock"></i> Login Sistem Penjualan
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(session('error'))
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle"></i>
                            {{ session('error') }}
                        </div>
                        @endif

                        @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                            <div><i class="bi bi-x-circle"></i> {{ $error }}</div>
                            @endforeach
                        </div>
                        @endif

                        @if(session('success'))
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle"></i>
                            {{ session('success') }}
                        </div>
                        @endif

                        <form action="{{ route('login.post') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </button>
                        </form>

                        <div class="mt-3 text-center">
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i>
                                Satu akun hanya bisa digunakan di satu perangkat
                            </small>
                        </div>
                    </div>
                </div>
                <p class="text-center mt-3 text-muted">
                    &copy; {{ date('Y') }} Sistem Penjualan
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>