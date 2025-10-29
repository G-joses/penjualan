@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
    .text-bg-purple {
        background-color: #6f42c1 !important;
        color: white !important;
    }

    .small-box {
        border-radius: 10px;
        transition: transform 0.2s;
    }

    .small-box:hover {
        transform: translateY(-5px);
    }

    .small-box-icon {
        position: absolute;
        top: 15px;
        right: 15px;
        font-size: 2.5rem;
        opacity: 0.3;
    }

    /* Styling untuk backdrop/modal background */
    .modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        /* Semi-transparan */
        z-index: 1040;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* Styling untuk popup/modal content */
    .modal-content {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        z-index: 1050;
        min-width: 300px;
        text-align: center;
    }
</style>
<div>
    <div class="row">
        <div>
            <div>
                <div class="card-body">
                    <div class="row">
                        <!-- Cards statistik (tetap sama) -->
                        <div class="col-lg-3 col-6">
                            <div class="small-box text-bg-primary">
                                <div class="inner">
                                    <h3>{{ $jumlahBarang }}</h3>
                                    <p>Jumlah Barang</p>
                                </div>
                                <i class="bi bi-box-seam small-box-icon"></i>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box text-bg-success">
                                <div class="inner">
                                    <h3>{{ $jumlahKategori }}</h3>
                                    <p>Jumlah Kategori</p>
                                </div>
                                <i class="bi bi-tags small-box-icon"></i>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box text-bg-warning">
                                <div class="inner">
                                    <h3>{{ $transaksiBulanIni }}</h3>
                                    <p>Transaksi Bulan {{ $bulan }}</p>
                                </div>
                                <i class="bi bi-cart-check small-box-icon"></i>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box text-bg-dark">
                                <div class="inner">
                                    <h3>{{ $jumlahUser }}</h3>
                                    <p>Jumlah User</p>
                                </div>
                                <i class="bi bi-people small-box-icon"></i>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box text-bg-info">
                                <div class="inner">
                                    <h3>Rp {{ number_format($totalPenjualanHariIni, 0, ',', '.') }}</h3>
                                    <p>Penjualan Hari Ini</p>
                                </div>
                                <i class="bi bi-currency-dollar small-box-icon"></i>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box text-bg-secondary">
                                <div class="inner">
                                    <h3>Rp {{ number_format($totalPenjualanBulanIni, 0, ',', '.') }}</h3>
                                    <p>Penjualan Bulan Ini</p>
                                </div>
                                <i class="bi bi-graph-up-arrow small-box-icon"></i>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box text-bg-danger">
                                <div class="inner">
                                    <h3>{{ $jumlahLowStock }}</h3>
                                    <p>Barang Low Stock</p>
                                </div>
                                <i class="bi bi-exclamation-triangle small-box-icon"></i>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box text-bg-purple">
                                <div class="inner">
                                    <h3>{{ $transaksiHariIni }}</h3>
                                    <p>Transaksi Hari Ini</p>
                                </div>
                                <i class="bi bi-cart-plus small-box-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!--begin::quick button-->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-lightning"></i> Quick Actions
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-wrap justify-content-center gap-3">
                                    <a href="{{ route('products.create') }}" class="btn btn-primary btn-lg py-3" style="min-width: 200px;">
                                        <i class="bi bi-plus me-2"></i> Tambah Produk
                                    </a>
                                    <a href="{{ route('products.index') }}" class="btn btn-warning btn-lg py-3" style="min-width: 200px;">
                                        <i class="bi bi-box-arrow-in-right me-2"></i> Cek Stok
                                    </a>
                                    <a href="{{ route('kasir.index') }}" class="btn btn-success btn-lg py-3" style="min-width: 200px;">
                                        <i class="bi bi-cart-plus me-2"></i> Transaksi Baru
                                    </a>

                                    <!-- ✅ TOMBOL CETAK LAPORAN DENGAN MODAL -->
                                    <button type="button" class="btn btn-info btn-lg py-3" style="min-width: 200px;"
                                        data-bs-toggle="modal" data-bs-target="#cetakLaporanModal">
                                        <i class="bi bi-printer me-2"></i> Cetak Laporan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::quick button-->

                <!-- Grafik, produk terlaris, transaksi terbaru (tetap sama) -->
                <div class="container mt-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-center mb-3">📈 Grafik Penjualan Tahun {{ date('Y') }}</h5>
                            <canvas id="salesChart" height="100"></canvas>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-trophy"></i> 5 Produk Terlaris
                                </h5>
                            </div>
                            <div class="card-body">
                                @foreach($produkTerlaris as $detail)
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h6 class="mb-1">{{ $detail->product->name ?? 'Produk Tidak Ditemukan' }}</h6>
                                        <small class="text-muted">
                                            Dengan Harga: Rp {{ number_format($detail->rata_harga, 0, ',', '.') }}
                                        </small>
                                    </div>
                                    <span class="badge bg-success">Terjual :{{ $detail->total_terjual }} pcs</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-exclamation-triangle"></i> Produk Stok Menipis
                                </h5>
                            </div>
                            <div class="card-body">
                                @foreach($produkLowStock as $produk)
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h6 class="mb-1">{{ $produk->name }}</h6>
                                        <small class="text-muted">Stok: {{ $produk->stock }}</small>
                                    </div>
                                    <span class="badge bg-warning">Restock</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-clock-history"></i> Transaksi Terbaru
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>Invoice</th>
                                                <th>QTY</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($transaksiTerbaru as $transaksi)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $transaksi->created_at->format('d/m/Y H:i') }}</td>
                                                <td>{{ $transaksi->invoice}}</td>
                                                <td>{{ $transaksi->details_count }}</td>
                                                <td>Rp {{ number_format($transaksi->total, 0, ',', '.') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-center mt-3">
                                    <a href="{{ route('laporan.index') }}" class="btn btn-outline-primary btn-sm">
                                        Lihat Semua Transaksi
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal Cetak Laporan -->
<div class="modal fade" id="cetakLaporanModal" tabindex="-1" aria-labelledby="cetakLaporanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="cetakLaporanModalLabel">
                    <i class="bi bi-printer me-2"></i> Cetak Laporan Bulanan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- ✅ FORM CETAK LAPORAN SEDERHANA -->
                <form id="formCetakLaporan" method="GET" action="{{ route('laporan.cetakBulanan') }}" target="_blank">
                    <div class="mb-3">
                        <label for="bulan_cetak" class="form-label fw-semibold">Pilih Bulan</label>
                        <input type="month" class="form-control" id="bulan_cetak" name="bulan"
                            value="{{ date('Y-m') }}" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="formCetakLaporan" class="btn btn-primary">
                    <i class="bi bi-printer me-2"></i> Cetak Laporan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart.js (tetap sama)
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
            ],
            datasets: [{
                label: 'Total Penjualan (Rp)',
                data: @json($monthlySales),
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => 'Rp ' + value.toLocaleString()
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: context => 'Rp ' + context.raw.toLocaleString()
                    }
                }
            }
        }
    });

    // ✅ SEDERHANA: Tidak ada JavaScript tambahan untuk modal
    // Biarkan Bootstrap handle modal secara natural
</script>

@endsection