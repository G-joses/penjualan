@extends('layouts.app')

@section('title', 'Data Kategori')

@section('content')
<div>
    <div class="row">
        <div>
            <div>
                <div class="card-body">
                    <a href="{{ route('category.create') }}" class="btn btn-md btn-success mb-3">Tambah Kategori Baru</a>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">NO</th>
                                <th scope="col" class="text-center">NAMA KATEOGRI</th>
                                <th scope="col" class="text-center">DESKRIPSI</th>
                                <th scope="col" style="width: 20%" class="text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($category as $cat)
                            <tr>
                                <td>{{ ($category->firstitem() ?? 0) + $loop->index}}</td>
                                <td>{{ $cat->name }}</td>
                                <td>{!! $cat->description !!}</td>
                                <td class="text-center">
                                    <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ route('category.destroy', $cat->id) }}" method="POST">
                                        <a href="{{ route('category.edit', $cat->id) }}" class="btn btn-sm btn-primary">UBAH</a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">HAPUS</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <div class="alert alert-danger">
                                Waduh Data Kategori Belum Ada !!!, Klik Tambah Barang Baru Untuk Tambah Kategori.
                            </div>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $category->links() }}
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