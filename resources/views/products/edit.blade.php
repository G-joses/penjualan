@extends('layouts.app')

@section('title', 'Edit Barang')

@section('content')


<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-body">
                    <form action="{{ route('products.update', $products->id) }}" method="POST" enctype="multipart/form-data">

                        @csrf
                        @method('PUT')

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
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $products->name) }}" placeholder="Masukkan Nama Barang">

                            <!-- error message untuk title -->
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
                                <option value="{{ $category->id }}"
                                    {{ $products->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">DESKRIPSI</label>
                            <textarea id="summernote" class="form-control @error('description') is-invalid @enderror" name="description" rows="5" placeholder="Masukkan Description Product">{{ old('description', $products->description) }}</textarea>

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
                                    <label class="font-weight-bold">HARGA NORMAL</label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price"
                                        value="{{ old('price', $products->price) }}" placeholder="Masukkan Harga Barang" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold">STOK</label>
                                    <input type="number" class="form-control @error('stock') is-invalid @enderror" name="stock"
                                        value="{{ old('stock', $products->stock) }}" placeholder="Masukkan Stok Product">
                                </div>
                            </div>
                        </div>

                        <!-- DISKON SECTION UNTUK EDIT -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="has_discount" name="has_discount"
                                        value="1" {{ old('has_discount', $products->has_discount) ? 'checked' : '' }}>
                                    <label class="form-check-label font-weight-bold" for="has_discount">AKTIFKAN DISKON</label>
                                </div>
                            </div>
                        </div>

                        <div id="discount-section" style="{{ old('has_discount', $products->has_discount) ? '' : 'display: none;' }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold">JENIS DISKON</label>
                                        <select class="form-select" name="discount_type" id="discount_type">
                                            <option value="percent" {{ old('discount_type', $products->discount > 0 ? 'percent' : 'amount') == 'percent' ? 'selected' : '' }}>Persentase (%)</option>
                                            <option value="amount" {{ old('discount_type', $products->discount_amount > 0 ? 'amount' : 'percent') == 'amount' ? 'selected' : '' }}>Nominal (Rp)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold">NILAI DISKON</label>
                                        <input type="number" class="form-control" id="discount_value" name="discount_value"
                                            value="{{ old('discount_value', $products->discount > 0 ? $products->discount : $products->discount_amount) }}"
                                            min="0" step="0.1">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <div class="alert alert-info">
                                    <strong>HARGA SETELAH DISKON: </strong>
                                    <span id="final-price-display">Rp {{ number_format($products->price_after_discount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                </div>

                <div class="card-footer bg-white border-0">
                    <div class="d-flex justify-content-between">
                        <div>
                            <button type="submit" class="btn btn-success me-2">UPDATE</button>
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
    // Fungsi untuk menampilkan/menyembunyikan section diskon
    function toggleDiscountSection() {
        if ($('#has_discount').is(':checked')) {
            $('#discount-section').show();
        } else {
            $('#discount-section').hide();
        }
    }

    // Event listener untuk checkbox diskon
    $('#has_discount').change(function() {
        toggleDiscountSection();
    });

    // Fungsi untuk menghitung harga setelah diskon
    function calculateFinalPrice() {
        const price = parseFloat($('#price').val()) || 0;
        const discountType = $('#discount_type').val();
        const discountValue = parseFloat($('#discount_value').val()) || 0;

        let finalPrice = price;

        if (discountType === 'percent') {
            finalPrice = price - (price * discountValue / 100);
        } else if (discountType === 'amount') {
            finalPrice = price - discountValue;
        }

        // Pastikan harga tidak negatif
        finalPrice = Math.max(0, finalPrice);

        $('#final-price-display').text('Rp ' + finalPrice.toLocaleString('id-ID'));
    }

    // Event listeners untuk menghitung harga real-time
    $('#price, #discount_type, #discount_value').on('input change', function() {
        if ($('#has_discount').is(':checked')) {
            calculateFinalPrice();
        }
    });

    // Inisialisasi awal
    toggleDiscountSection();
    if ($('#has_discount').is(':checked')) {
        calculateFinalPrice();
    }
</script>

@endsection