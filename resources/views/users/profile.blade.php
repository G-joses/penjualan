@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="container mt-4">

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form method="POST" action="{{ route('user.updateProfile') }}">
        @csrf

        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>

        <hr>
        <h6>Ganti Password (Opsional)</h6>

        <div class="mb-3">
            <label>Password Lama</label>
            <input type="password" name="password_lama" class="form-control" placeholder="Isi jika ingin ganti password">
        </div>

        <div class="mb-3">
            <label>Password Baru</label>
            <input type="password" name="password_baru" class="form-control" placeholder="Isi jika ingin ganti password">
        </div>
        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
    </form>
</div>
@endsection