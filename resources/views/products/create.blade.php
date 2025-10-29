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
                            @error('image')
                            <div class="alert alert-danger mt-2">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold">NAMA BARANG</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Masukkan Nama Barang">
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
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold">DESKRIPSI</label>
                            <textarea id="summernote" class="form-control @error('description') is-invalid @enderror" name="description" rows="5" placeholder="Masukkan Deskripsi Barang">{{ old('description') }}</textarea>
                            @error('description')
                            <div class="alert alert-danger mt-2">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <!-- 🎯 BAGIAN HARGA DAN DISKON YANG SUDAH DIPERBAIKI -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold">HARGA NORMAL</label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" placeholder="Masukkan Harga Barang" required>
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
                                    @error('stock')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- 🎯 BAGIAN DISKON YANG SUDAH DIPERBAIKI -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="has_discount" name="has_discount" value="1" {{ old('has_discount') ? 'checked' : '' }}>
                                    <label class="form-check-label font-weight-bold" for="has_discount">AKTIFKAN DISKON</label>
                                </div>
                            </div>
                        </div>

                        <!-- FORM DISKON -->
                        <div id="discount-section" style="{{ old('has_discount') ? '' : 'display: none;' }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold">JENIS DISKON</label>
                                        <select class="form-select" name="discount_type" id="discount_type">
                                            <option value="percent" {{ old('discount_type', 'percent') == 'percent' ? 'selected' : '' }}>Persentase (%)</option>
                                            <option value="amount" {{ old('discount_type') == 'amount' ? 'selected' : '' }}>Nominal (Rp)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold">NILAI DISKON</label>
                                        <input type="number" class="form-control" id="discount_value" name="discount_value" value="{{ old('discount_value', 0) }}" min="0" step="0.1">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <div class="alert alert-info">
                                    <strong>HARGA SETELAH DISKON: </strong>
                                    <span id="final-price-display">Rp 0</span>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-white border-0">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <button type="submit" class="btn btn-success me-2">TAMBAH</button>
                                    <button type="reset" class="btn btn-danger me-2">HAPUS</button>
                                </div>
                                <a href="{{ route('products.index') }}" class="btn btn-primary">KEMBALI</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Summernote CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            placeholder: 'Masukkan deskripsi produk...',
            tabsize: 2,
            height: 200
        });

        // Inisialisasi perhitungan harga
        calculateFinalPrice();
    });

    document.getElementById('has_discount').addEventListener('change', function() {
        document.getElementById('discount-section').style.display = this.checked ? 'block' : 'none';
        calculateFinalPrice();
    });

    document.getElementById('discount_type').addEventListener('change', calculateFinalPrice);
    document.getElementById('price').addEventListener('input', calculateFinalPrice);
    document.getElementById('discount_value').addEventListener('input', calculateFinalPrice);

    function calculateFinalPrice() {
        const price = parseFloat(document.getElementById('price').value) || 0;
        const hasDiscount = document.getElementById('has_discount').checked;
        const discountType = document.getElementById('discount_type').value;
        const discountValue = parseFloat(document.getElementById('discount_value').value) || 0;

        let finalPrice = price;

        if (hasDiscount && discountValue > 0) {
            if (discountType === 'percent') {
                finalPrice = price * (1 - (discountValue / 100));
            } else {
                finalPrice = Math.max(0, price - discountValue);
            }
        }

        document.getElementById('final-price-display').textContent =
            'Rp ' + finalPrice.toLocaleString('id-ID');
    }
</script>
@endsection