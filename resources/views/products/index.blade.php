@extends('layouts.app')

@section('title', 'Data Barang')

@section('content')
<div class="container mt-4">
    {{-- 🔍 Header Section - Responsif --}}
    <div class="row mb-4 g-3 align-items-center">
        {{-- Tombol Tambah Barang --}}
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
            @if(auth()->user()->role == 'admin')
            <a href="{{ route('products.create') }}" class="btn btn-primary w-100">
                <i class="nav-icon bi bi-plus me-2"></i>Tambah Barang Baru
            </a>
            @endif
        </div>

        {{-- Pencarian --}}
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
            <form method="GET" action="{{ route('products.index') }}" id="searchForm">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama barang..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-secondary">
                        <i class="nav-icon bi bi-search"></i>
                    </button>
                </div>
                <input type="hidden" name="per_page" value="{{ $perPage }}">
                <input type="hidden" name="price_sort" value="{{ request('price_sort') }}">
                <input type="hidden" name="category" value="{{ request('category') }}">
                <input type="hidden" name="stock_sort" value="{{ request('stock_sort') }}">
            </form>
        </div>

        {{-- Jumlah Tampilan --}}
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
            <form method="GET" action="{{ route('products.index') }}" id="perPageForm">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <input type="hidden" name="price_sort" value="{{ request('price_sort') }}">
                <input type="hidden" name="category" value="{{ request('category') }}">
                <input type="hidden" name="stock_sort" value="{{ request('stock_sort') }}">

                <div class="input-group">
                    <label class="input-group-text"><i class="fas fa-list me-2"></i>Tampil:</label>
                    <select name="per_page" class="form-select" onchange="document.getElementById('perPageForm').submit()">
                        <option value="8" {{ $perPage == 8 ? 'selected' : '' }}>8 Barang</option>
                        <option value="12" {{ $perPage == 12 ? 'selected' : '' }}>12 Barang</option>
                        <option value="16" {{ $perPage == 16 ? 'selected' : '' }}>16 Barang</option>
                        <option value="24" {{ $perPage == 24 ? 'selected' : '' }}>24 Barang</option>
                    </select>
                </div>
            </form>
        </div>

        {{-- Tombol Filter Mobile --}}
        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12 d-block d-lg-none">
            <button class="btn btn-outline-primary w-100" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                <i class="fas fa-filter me-2"></i>Filter
            </button>
        </div>
    </div>

    {{-- 🎛️ Filter Section --}}
    <div class="collapse d-lg-block mb-4" id="filterCollapse">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-sliders-h me-2"></i>Filter Barang</h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('products.index') }}" id="filterForm">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="per_page" value="{{ $perPage }}">

                    <div class="row g-3">
                        {{-- Filter Harga --}}
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <label class="form-label fw-bold">Urutkan Harga</label>
                            <select name="price_sort" class="form-select" onchange="document.getElementById('filterForm').submit()">
                                <option value="">Pilih urutan harga</option>
                                <option value="price_low" {{ request('price_sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah ke Tertinggi</option>
                                <option value="price_high" {{ request('price_sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi ke Terendah</option>
                            </select>
                        </div>

                        {{-- Filter Kategori --}}
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <label class="form-label fw-bold">Filter Kategori</label>
                            <select name="category" class="form-select" onchange="document.getElementById('filterForm').submit()">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filter Stok --}}
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <label class="form-label fw-bold">Urutkan Stok</label>
                            <select name="stock_sort" class="form-select" onchange="document.getElementById('filterForm').submit()">
                                <option value="">Pilih urutan stok</option>
                                <option value="stock_high" {{ request('stock_sort') == 'stock_high' ? 'selected' : '' }}>Stok Tertinggi ke Terendah</option>
                                <option value="stock_low" {{ request('stock_sort') == 'stock_low' ? 'selected' : '' }}>Stok Terendah ke Tertinggi</option>
                            </select>
                        </div>

                        {{-- Tombol Reset --}}
                        <div class="col-lg-3 col-md-6 col-sm-12 d-flex align-items-end">
                            <a href="{{ route('products.index', ['per_page' => $perPage]) }}" class="btn btn-outline-danger w-100">
                                <i class="fas fa-times me-2"></i>Reset Filter
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Info Filter Aktif --}}
    @if(request('price_sort') || request('category') || request('stock_sort'))
    <div class="alert alert-info mb-3">
        <strong><i class="fas fa-info-circle me-2"></i>Filter Aktif:</strong>
        @if(request('price_sort'))
        <span class="badge bg-primary ms-2">
            <i class="fas fa-sort-amount-{{ request('price_sort') == 'price_low' ? 'down' : 'up' }} me-1"></i>
            Harga: {{ request('price_sort') == 'price_low' ? 'Terendah ke Tertinggi' : 'Tertinggi ke Terendah' }}
        </span>
        @endif

        @if(request('category'))
        <span class="badge bg-success ms-2">
            <i class="fas fa-tag me-1"></i>
            Kategori: {{ $categories->where('id', request('category'))->first()->name }}
        </span>
        @endif

        @if(request('stock_sort'))
        <span class="badge bg-warning ms-2">
            <i class="fas fa-boxes me-1"></i>
            Stok: {{ request('stock_sort') == 'stock_high' ? 'Tertinggi ke Terendah' : 'Terendah ke Tertinggi' }}
        </span>
        @endif
    </div>
    @endif

    {{-- Info Jumlah Data --}}
    <div class="mb-3 text-muted">
        <small>
            <i class="fas fa-cube me-1"></i>
            Menampilkan {{ $products->count() }} dari {{ $products->total() }} barang
            @if(request('search'))
            untuk pencarian "{{ request('search') }}"
            @endif
        </small>
    </div>

    {{-- 🧩 Daftar Produk --}}
    @if($products->count() > 0)
    <div class="row" id="productList">
        @foreach($products as $p)
        <div class="col-xxl-3 col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4 product-item">
            <div class="card text-center shadow-sm h-100 product-card">
                <div class="card-body d-flex flex-column">
                    <div class="mb-3 flex-shrink-0">
                        <img src="{{ asset('storage/products/'.$p->image) }}" class="product-image img-fluid"
                            alt="{{ $p->name }}" style="width: 120px; height: 120px; object-fit: cover;">
                    </div>
                    <h6 class="product-name fw-bold mb-2 text-truncate" title="{{ $p->name }}">{{ $p->name }}</h6>
                    <p class="text-muted mb-1 fs-6 fw-bold text-success">Rp {{ number_format($p->final_price, 0, ',', '.') }}</p>
                    <p class="text-muted mb-3">
                        <small class="badge bg-{{ $p->stock > 10 ? 'success' : ($p->stock > 0 ? 'warning' : 'danger') }}">
                            Stok: {{ $p->stock }}
                        </small>
                    </p>
                    <div class="mt-auto">
                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                            <a href="{{ route('products.show', $p->id) }}" class="btn btn-outline-secondary btn-sm" title="Lihat Detail">
                                <i class="nav-icon bi bi-eye"></i>
                            </a>
                            @if(auth()->user()->role == 'admin')
                            <a href="{{ route('products.edit', $p->id) }}" class="btn btn-outline-primary btn-sm" title="Edit">
                                <i class="nav-icon bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('products.destroy', $p->id) }}" method="POST"
                                onsubmit="return confirm('Yakin hapus {{ $p->name }}?')" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm" title="Hapus">
                                    <i class="nav-icon bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-5">
        <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
        <h5 class="text-muted">Tidak ada produk ditemukan</h5>
        <p class="text-muted">Coba ubah filter atau kata kunci pencarian</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">
            <i class="fas fa-refresh me-2"></i>Reset Pencarian
        </a>
    </div>
    @endif

    {{-- 🔢 Pagination --}}
    @if($products->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $products->onEachSide(1)->links() }}
    </div>
    @endif
</div>

<style>
    .product-card {
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
        border-color: #007bff;
    }

    .product-image {
        transition: transform 0.3s ease;
    }

    .product-card:hover .product-image {
        transform: scale(1.1);
    }

    /* Responsive adjustments */
    @media (max-width: 576px) {
        .container {
            padding-left: 10px;
            padding-right: 10px;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }

        .card-body {
            padding: 1rem;
        }
    }

    @media (max-width: 768px) {
        .col-sm-12 {
            margin-bottom: 10px;
        }
    }
</style>

<script>
    // Auto close filter collapse on mobile after selection
    document.addEventListener('DOMContentLoaded', function() {
        const filterSelects = document.querySelectorAll('#filterCollapse select');
        const filterCollapse = document.getElementById('filterCollapse');

        filterSelects.forEach(select => {
            select.addEventListener('change', function() {
                // Close collapse on mobile after a short delay
                if (window.innerWidth < 992) {
                    setTimeout(() => {
                        const bsCollapse = new bootstrap.Collapse(filterCollapse);
                        bsCollapse.hide();
                    }, 500);
                }
            });
        });
    });
</script>
@endsection