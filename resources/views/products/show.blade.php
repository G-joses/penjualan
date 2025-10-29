@extends('layouts.app')

@section('title', 'Detail Barang')

@section('content')

<div>
    <div class="row">
        <div>
            <div>
                <div class="card-body">
                    <a href="{{ route('products.index') }}" class="btn btn-md btn-primary mb-3">KEMBALI</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-body">
                    <img src="{{ asset('/storage/products/'.$products->image) }}" class="rounded" style="width: 100%">
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-body">
                    <h3>{{ $products->name }}</h3>
                    <p><strong>Kategori :</strong> {{ $products->category ? $products->category->name : '-' }}</p>
                    <hr />
                    <p>{{ "Rp " . number_format($products->price,2,',','.') }}</p>
                    <code>
                        <p>{!! $products->description !!}</p>
                    </code>
                    <hr />
                    <p>Stock : {{ $products->stock }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection