@extends('layouts.app')

@section('title', 'Data Barang')

@section('content')
<div class="container mt-4">

    {{-- 🔍 Search dan Jumlah per halaman --}}
    <div class="d-flex justify-content-between mb-3">
        @if(auth()->user()->role == 'admin')
        <a href="{{ route('products.create') }}" class="btn btn-md btn-primary mb-3">+ Tambah Barang Baru</a>
        @endif
        <input type="text" id="searchProduct" class="form-control w-50" placeholder="Cari nama barang..." value="{{ request('search') }}">

        <form method="GET" action="{{ route('products.index') }}" id="perPageForm">
            <label class="me-2">Tampilkan:</label>
            <select name="per_page" id="per_page" class="form-select d-inline-block" style="width: auto;" onchange="document.getElementById('perPageForm').submit()">
                <option value="8" {{ $perPage == 8 ? 'selected' : '' }}>8</option>
                <option value="12" {{ $perPage == 12 ? 'selected' : '' }}>12</option>
                <option value="16" {{ $perPage == 16 ? 'selected' : '' }}>16</option>
                <option value="24" {{ $perPage == 24 ? 'selected' : '' }}>24</option>
            </select>
        </form>
    </div>

    {{-- 🧩 Daftar Produk --}}
    <div class="row" id="productList">
        @foreach($products as $p)
        <div class="col-md-3 col-sm-6 mb-4 product-item">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body">
                    <img src="{{ asset('storage/products/'.$p->image) }}" class="mb-3" style="width: 80px; height: 80px; object-fit: cover;">
                    <h6 class="product-name">{{ $p->name }}</h6>
                    <p class="text-muted mb-1">Rp {{ number_format($p->final_price, 0, ',', '.') }}</p>
                    <p class="text-muted mb-1">Stok : {{ $p->stock }}</p>
                    <div class="d-flex justify-content-center mt-2">
                        <a href="{{ route('products.show', $p->id) }}" class="btn btn-secondary btn-sm me-1">LIHAT</a>
                        @if(auth()->user()->role == 'admin')
                        <a href="{{ route('products.edit', $p->id) }}" class="btn btn-primary btn-sm me-1">EDIT</a>
                        <form action="{{ route('products.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">HAPUS</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- 🔢 Pagination --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $products->links() }}
    </div>
</div>

{{-- 🔎 Script pencarian realtime --}}
<script>
    document.getElementById('searchProduct').addEventListener('keyup', function() {
        const searchText = this.value.toLowerCase();
        const products = document.querySelectorAll('#productList .product-item');

        products.forEach(product => {
            const name = product.querySelector('.product-name').textContent.toLowerCase();
            product.style.display = name.includes(searchText) ? '' : 'none';
        });
    });
</script>
@endsection