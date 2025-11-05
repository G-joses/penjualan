@extends('layouts.app')

@section('title', 'Daftar Transaksi')

@section('content')
<div class="container">
    <!-- Form Cetak Laporan -->
    <form method="GET" action="{{ route('laporan.cetakBulanan') }}" target="_blank" class="row g-3 mt-3">
        <div class="col-md-4">
            <label for="bulan" class="form-label">Pilih Bulan</label>
            <input type="month" id="bulan" name="bulan" class="form-control" value="{{ date('Y-m') }}">
        </div>
        <div class="col-md-4 align-self-end">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-printer"></i> Cetak Laporan Bulanan
            </button>
        </div>
    </form>

    <br><br>

    <!-- Form Hapus Transaksi Bulanan -->
    <div class="card mb-4">
        <div class="card-header bg-warning text-dark">
            <h5 class="card-title mb-0">
                <i class="bi bi-trash"></i> Hapus Transaksi Berdasarkan Bulan
            </h5>
        </div>
        <div class="card-body">
            <form id="form-hapus-bulanan" class="row g-3">
                @csrf
                <div class="col-md-4">
                    <label for="bulan_hapus" class="form-label">Pilih Bulan yang Akan Dihapus</label>
                    <input type="month" id="bulan_hapus" name="bulan" class="form-control"
                        max="{{ now()->subMonth()->format('Y-m') }}"
                        value="{{ now()->subMonth()->format('Y-m') }}">
                    <div class="form-text text-danger">
                        <i class="bi bi-exclamation-triangle"></i>
                        Tidak bisa menghapus transaksi bulan berjalan
                    </div>
                </div>
                <div class="col-md-4 align-self-end">
                    <button type="button" id="btn-check-bulan" class="btn btn-warning">
                        <i class="bi bi-search"></i> Cek Data
                    </button>
                    <button type="button" id="btn-hapus-bulan" class="btn btn-danger" disabled>
                        <i class="bi bi-trash"></i> Hapus Transaksi
                    </button>
                </div>
            </form>

            <!-- Statistik Bulanan -->
            <div id="statistik-bulanan" class="mt-3" style="display: none;">
                <div class="alert alert-info">
                    <h6>Statistik Bulan: <span id="periode-statistik"></span></h6>
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Total Transaksi:</strong> <span id="total-transaksi">0</span>
                        </div>
                        <div class="col-md-3">
                            <strong>Total Pendapatan:</strong> Rp <span id="total-pendapatan">0</span>
                        </div>
                        <div class="col-md-3">
                            <strong>Item Terjual:</strong> <span id="total-item">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Filter Tanggal -->
    <form method="GET" action="{{ route('laporan.index') }}" class="row g-3 mb-4">
        <div class="col-md-4">
            <label>Dari Tanggal</label>
            <input type="date" name="from" class="form-control" value="{{ $from }}">
        </div>
        <div class="col-md-4">
            <label>Sampai Tanggal</label>
            <input type="date" name="to" class="form-control" value="{{ $to }}">
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </form>

    <!-- Tabel Transaksi -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama</th>
                <th>Total</th>
                <th>Jumlah Item</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $t)
            <tr>
                <td>{{ $t->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $t->customer_name }}</td>
                <td>Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                <td>{{ $t->details->count() }}</td>
                <td>
                    <a href="{{ route('kasir.struk', $t->id) }}" class="btn btn-sm btn-success" target="_blank">
                        <i class="bi bi-printer"></i> Cetak Struk
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <br>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" data-bs-backdrop="false>
    <div class=" modal-dialog">
    <div class="modal-content">
        <div class="modal-header bg-danger text-white">
            <h5 class="modal-title">
                <i class="bi bi-exclamation-triangle"></i> Konfirmasi Hapus Transaksi
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <p>Anda akan menghapus semua transaksi pada:</p>
            <div class="alert alert-warning">
                <strong id="modal-periode"></strong>
            </div>
            <div id="modal-statistik"></div>
            <p class="text-danger fw-bold">
                <i class="bi bi-exclamation-triangle"></i>
                Tindakan ini tidak dapat dibatalkan! Stok produk akan dikembalikan.
            </p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="button" id="confirm-delete-btn" class="btn btn-danger">Ya, Hapus Transaksi</button>
        </div>
    </div>
</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const bulanHapus = document.getElementById('bulan_hapus');
        const btnCheck = document.getElementById('btn-check-bulan');
        const btnHapus = document.getElementById('btn-hapus-bulan');
        const statistikDiv = document.getElementById('statistik-bulanan');
        const confirmDeleteBtn = document.getElementById('confirm-delete-btn');

        // Cek data bulanan
        btnCheck.addEventListener('click', function() {
            const bulan = bulanHapus.value;

            if (!bulan) {
                alert('Pilih bulan terlebih dahulu!');
                return;
            }

            // Cek jika bulan berjalan
            const currentMonth = new Date().toISOString().slice(0, 7);
            if (bulan === currentMonth) {
                alert('Tidak bisa menghapus transaksi bulan berjalan!');
                statistikDiv.style.display = 'none';
                btnHapus.disabled = true;
                return;
            }

            // Tampilkan loading
            btnCheck.disabled = true;
            btnCheck.innerHTML = '<i class="bi bi-hourglass-split"></i> Loading...';

            fetch(`/laporan/statistik-bulanan?bulan=${bulan}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const stat = data.statistik;
                        document.getElementById('periode-statistik').textContent = stat.periode;
                        document.getElementById('total-transaksi').textContent = stat.total_transaksi.toLocaleString();
                        document.getElementById('total-pendapatan').textContent = stat.total_pendapatan.toLocaleString();
                        document.getElementById('total-item').textContent = stat.total_item_terjual.toLocaleString();

                        statistikDiv.style.display = 'block';
                        btnHapus.disabled = stat.total_transaksi === 0;

                        // Set data untuk modal
                        document.getElementById('modal-periode').textContent = stat.periode;
                        document.getElementById('modal-statistik').innerHTML = `
                            <p><strong>Detail yang akan dihapus:</strong></p>
                            <ul>
                                <li>Total Transaksi: ${stat.total_transaksi}</li>
                                <li>Total Pendapatan: Rp ${stat.total_pendapatan.toLocaleString()}</li>
                                <li>Item Terjual: ${stat.total_item_terjual}</li>
                            </ul>
                        `;
                    } else {
                        alert(data.message);
                        statistikDiv.style.display = 'none';
                        btnHapus.disabled = true;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengambil data! Periksa console untuk detail.');
                })
                .finally(() => {
                    // Reset button
                    btnCheck.disabled = false;
                    btnCheck.innerHTML = '<i class="bi bi-search"></i> Cek Data';
                });
        });

        // Tombol hapus klik
        btnHapus.addEventListener('click', function() {
            const bulan = bulanHapus.value;
            if (!bulan) {
                alert('Pilih bulan terlebih dahulu!');
                return;
            }

            // Tampilkan modal konfirmasi
            const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
            modal.show();
        });

        // Konfirmasi hapus dari modal
        confirmDeleteBtn.addEventListener('click', function() {
            const bulan = bulanHapus.value;

            // Tampilkan loading
            confirmDeleteBtn.disabled = true;
            confirmDeleteBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Menghapus...';

            fetch('/laporan/hapus-bulanan', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        bulan: bulan
                    })
                })
                .then(response => response.json())
                .then(data => {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModal'));
                    modal.hide();

                    if (data.success) {
                        alert(data.message);
                        location.reload(); // Refresh halaman
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus data!');
                })
                .finally(() => {
                    // Reset button
                    confirmDeleteBtn.disabled = false;
                    confirmDeleteBtn.innerHTML = 'Ya, Hapus Transaksi';
                });
        });

        // Validasi input bulan
        bulanHapus.addEventListener('change', function() {
            statistikDiv.style.display = 'none';
            btnHapus.disabled = true;
        });
    });
</script>

<style>
    #statistik-bulanan .alert {
        border-left: 4px solid #0dcaf0;
    }
</style>
@endsection