@extends('layouts.app')

@section('title', 'Edit Barang')

@section('content')


<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-body">
                    <form action="{{ route('category.update', $category->id) }}" method="POST" enctype="multipart/form-data">

                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label class="font-weight-bold">NAMA KATEGORI</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $category->name) }}" placeholder="Masukkan Nama Kategori">

                            <!-- error message untuk title -->
                            @error('name')
                            <div class="alert alert-danger mt-2">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold">DESKRIPSI</label>
                            <textarea id="summernote" class="form-control @error('description') is-invalid @enderror" name="description" rows="5" placeholder="Masukkan Description Product">{{ old('description', $category->description) }}</textarea>

                            <!-- error message untuk description -->
                            @error('description')
                            <div class="alert alert-danger mt-2">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-md btn-success me-3">UPDATE</button>
                        <button type="reset" class="btn btn-md btn-danger me-3">HAPUS</button>
                        <a href="{{ route('category.index') }}" class="btn btn-md btn-primary">Kembali</a>
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