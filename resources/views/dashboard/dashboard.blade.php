@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div>
    <div class="row">
        <div>
            <div>
                <div class="card-body">
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-3 col-6">
                            <!--begin::Small Box Widget 1-->
                            <div class="small-box text-bg-primary">
                                <div class="inner">
                                    <h3>{{ $jumlahBarang }}</h3>
                                    <p>Barang</p>
                                </div>
                                <i class="bi bi-box-seam small-box-icon"></i>
                                <a
                                    href="{{ route('products.index') }}"
                                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                                    More info <i class="bi bi-link-45deg"></i>
                                </a>
                            </div>
                            <!--end::Small Box Widget 1-->
                        </div>
                        <!--end::Col-->
                        <div class="col-lg-3 col-6">
                            <!--begin::Small Box Widget 2-->
                            <div class="small-box text-bg-success">
                                <div class="inner">
                                    <h3>{{ $jumlahKategori }}</h3>
                                    <p>Kategori</p>
                                </div>
                                <i class="bi bi-tags small-box-icon"></i>
                                <a
                                    href="#"
                                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                                    More info <i class="bi bi-link-45deg"></i>
                                </a>
                            </div>
                            <!--end::Small Box Widget 2-->
                        </div>
                        <!--end::Col-->
                        <div class="col-lg-3 col-6">
                            <!--begin::Small Box Widget 3-->
                            <div class="small-box text-bg-warning">
                                <div class="inner">
                                    <h3>44</h3>
                                    <p>Transaksi</p>
                                </div>
                                <i class="bi bi-cart-check small-box-icon"></i>
                                <a
                                    href="#"
                                    class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover">
                                    More info <i class="bi bi-link-45deg"></i>
                                </a>
                            </div>
                            <!--end::Small Box Widget 3-->
                        </div>
                        <!--end::Col-->
                        <div class="col-lg-3 col-6">
                            <!--begin::Small Box Widget 4-->
                            <div class="small-box text-bg-danger">
                                <div class="inner">
                                    <h3>65</h3>
                                    <p>User</p>
                                </div>
                                <i class="bi bi-people small-box-icon"></i>
                                <a
                                    href="#"
                                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                                    More info <i class="bi bi-link-45deg"></i>
                                </a>
                            </div>
                            <!--end::Small Box Widget 4-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection