@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')



<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-body">
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">

                        @csrf

                        <div class="form-group mb-3">
                            <label class="font-weight-bold">GAMBAR</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" name="image">

                            <!-- error message untuk image -->
                            @error('image')
                            <div class="alert alert-danger mt-2">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold">NAMA BARANG</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Masukkan Nama Barang">

                            <!-- error message untuk name -->
                            @error('name')
                            <div class="alert alert-danger mt-2">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="category_id" class="font-weight-bold">KATEGORI</label>
                            <select name="category_id" id="category_id" class="form-control">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">DESKRIPSI</label>
                            <textarea id="summernote" class="form-control @error('description') is-invalid @enderror" name="description" rows="5" placeholder="Masukkan Deskripsi Barang">{{ old('description') }}</textarea>

                            <!-- error message untuk description -->
                            @error('description')
                            <div class="alert alert-danger mt-2">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold">HARGA</label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price') }}" placeholder="Masukkan Harga Barang">

                                    <!-- error message untuk price -->
                                    @error('price')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold">STOK</label>
                                    <input type="number" class="form-control @error('stock') is-invalid @enderror" name="stock" value="{{ old('stock') }}" placeholder="Masukkan Stok Product">

                                    <!-- error message untuk stock -->
                                    @error('stock')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-md btn-success me-3">SIMPAN</button>
                        <button type="reset" class="btn btn-md btn-danger me-3 ">HAPUS</button>
                        <a href="{{ route('products.index') }}" class="btn btn-md btn-primary">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Summernote CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

<!-- jQuery (wajib untuk Summernote) -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>


<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            placeholder: 'Masukkan deskripsi produk...',
            tabsize: 2,
            height: 200
        });
    });
</script>

@endsection