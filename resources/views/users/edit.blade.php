@extends('layouts.app')

@section('title', 'Edit Akun User')

@section('content')
<div class="container mt-4">
    <div>
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
    </div>
    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-2">
            <label>Nama</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="mb-2">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>

        <hr>
        <h6>Ganti Password (Opsional)</h6>
        <div class="mb-2">
            <label for="password_lama">Password Lama</label>
            <input type="password" name="password_lama" id="password_lama" class="form-control" placeholder="Masukkan password lama">
        </div>

        <div class="mb-2">
            <label for="password_baru">Password Baru</label>
            <input type="password" name="password_baru" id="password_baru" class="form-control" placeholder="Masukkan password baru">
        </div>

        <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-control" required>
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="kasir" {{ $user->role == 'kasir' ? 'selected' : '' }}>Kasir</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">SIMPAN</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">BATAL</a>
    </form>
</div>
@endsection