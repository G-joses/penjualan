@extends('layouts.app')

@section('title', 'Data Barang')

@section('content')
<div>
    <div class="row">
        <div>
            <div>
                <div class="card-body">
                    <a href="{{ route('products.create') }}" class="btn btn-md btn-success mb-3">Tambah Barang Baru</a>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">NO</th>
                                <th scope="col" class="text-center">GAMBAR</th>
                                <th scope="col" class="text-center">NAMA</th>
                                <th scope="col" class="text-center">KATEGORI</th>
                                <th scope="col" class="text-center">HARGA</th>
                                <th scope="col" class="text-center">STOK</th>
                                <th scope="col" style="width: 20%" class="text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                            <tr>
                                <td>{{ ($products->firstitem() ?? 0) + $loop->index}}</td>
                                <td class="text-center">
                                    <img src="{{ asset('/storage/products/'.$product->image) }}" class="rounded" alt="{{ $product->nama }}" style="width: 80px; height: 80px; object-fit: cover;">
                                </td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->category->name ?? '-' }}</td>
                                <td>{{ "Rp " . number_format($product->price,2,',','.') }}</td>
                                <td>{{ $product->stock }}</td>
                                <td class="text-center">
                                    <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ route('products.destroy', $product->id) }}" method="POST">
                                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-dark">LIHAT</a>
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-primary">UBAH</a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">HAPUS</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <div class="alert alert-danger">
                                Waduh Data Barang Belum Ada !!!, Klik Tambah Barang Baru Untuk Tambah Barang.
                            </div>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    //message with sweetalert
    @if(session('success'))
    Swal.fire({
        icon: "success",
        title: "BERHASIL",
        text: "{{ session('success') }}",
        showConfirmButton: false,
        timer: 2000
    });
    @elseif(session('error'))
    Swal.fire({
        icon: "error",
        title: "GAGAL!",
        text: "{{ session('error') }}",
        showConfirmButton: false,
        timer: 2000
    });
    @endif
</script>
@endsection