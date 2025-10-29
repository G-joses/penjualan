@extends('layouts.app')

@section('title', 'Kasir')

@section('content')
<div class="container mt-4">
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form id="kasir-form" method="POST" action="{{ route('kasir.store') }}">
        @csrf
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="customer_name" class="form-label">
                        <i class="bi bi-person"></i> Nama Pelanggan
                    </label>
                    <input type="text"
                        name="customer_name"
                        id="customer_name"
                        class="form-control"
                        placeholder="Masukkan nama pelanggan (opsional)"
                        value="Pelanggan">
                    <small class="text-muted">Kosongkan untuk menggunakan 'Pelanggan'</small>
                </div>
            </div>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="cart-body"></tbody>
        </table>

        <!-- 🔧 BAGIAN PERHITUNGAN BARU -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-2">
                    <label>Subtotal:</label>
                    <input type="text" id="subtotal" class="form-control" readonly>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <label>Pajak (%):</label>
                            <input type="number" name="tax" id="tax" class="form-control" value="0" min="0" max="100" step="0.1">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <label>Diskon (Rp):</label>
                            <input type="number" name="discount" id="discount" class="form-control" value="0" min="0">
                        </div>
                    </div>
                </div>

                <div class="form-group mb-2">
                    <label>Total Pajak:</label>
                    <input type="text" id="tax-amount" class="form-control" readonly>
                </div>

                <div class="form-group mb-2">
                    <label>Total:</label>
                    <input type="text" id="total" class="form-control" readonly>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-2">
                    <label>Bayar:</label>
                    <input type="number" name="payment" id="payment" class="form-control" min="0">
                </div>

                <div class="form-group mb-2">
                    <label>Kembalian:</label>
                    <input type="text" id="change" class="form-control" readonly>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success btn-lg w-100">
                        <i class="bi bi-check-circle"></i> Simpan Transaksi
                    </button>
                </div>
            </div>
        </div>
    </form>

    <hr>
    <h5>Daftar Barang</h5>
    <div class="mb-3">
        <input type="text" id="searchProduct" class="form-control" placeholder="Cari nama barang...">
    </div>
    <div class="row" id="productList">
        @foreach($products as $p)
        <div class="col-md-3 product-item">
            <div class="card mb-3 text-center">
                <div class="card-body">
                    <img src="{{ asset('/storage/products/'.$p->image) }}" style="width: 80px; height: 80px; object-fit: cover;">
                    <h6 class="product-name">{{ $p->name }}</h6>

                    <!-- 🎯 TAMPILAN HARGA DENGAN DISKON -->
                    @if($p->has_discount)
                    <div>
                        <small class="text-muted text-decoration-line-through">
                            Rp {{ number_format($p->price, 0, ',', '.') }}
                        </small>
                        <p class="text-primary fw-bold mb-1">
                            Rp {{ number_format($p->price_after_discount, 0, ',', '.') }}
                        </p>
                        <span class="badge bg-success">
                            {{ number_format($p->discount, 0) }}%
                        </span>
                    </div>
                    @else
                    <p>Rp {{ number_format($p->price, 0, ',', '.') }}</p>
                    @endif

                    <button type="button" class="btn btn-primary btn-add"
                        data-id="{{ $p->id }}"
                        data-name="{{ $p->name }}"
                        data-price="{{ $p->price_after_discount }}" {{-- ✅ HARGA SETELAH DISKON --}}
                        data-original-price="{{ $p->price }}"
                        data-has-discount="{{ $p->has_discount ? '1' : '0' }}"
                        data-discount-display="{{ $p->discount_display ?? '' }}">
                        Tambah
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
    let cart = [];

    // 1. FUNGSI EVENT LISTENER UNTUK TOMBOL TAMBAH PRODUK
    document.querySelectorAll('.btn-add').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const name = btn.dataset.name;
            const price = parseFloat(btn.dataset.price); // Harga setelah diskon
            const originalPrice = parseFloat(btn.dataset.originalPrice);
            const hasDiscount = btn.dataset.hasDiscount === '1';
            const discountDisplay = btn.dataset.discountDisplay;

            const existing = cart.find(i => i.id === id);

            if (existing) {
                existing.qty++;
            } else {
                cart.push({
                    id,
                    name,
                    price, // ✅ Simpan harga setelah diskon
                    originalPrice,
                    hasDiscount,
                    discountDisplay,
                    qty: 1
                });
            }
            renderCart();
            calculateTotal();
        });
    });

    // 2. FUNGSI RENDER CART
    function renderCart() {
        let tbody = document.getElementById('cart-body');
        tbody.innerHTML = '';

        cart.forEach((item, index) => {
            let subtotal = item.qty * item.price;

            tbody.innerHTML += `
        <tr>
            <td>
                ${item.name}
                ${item.hasDiscount ? `<br><small class="text-success">Diskon: ${item.discountDisplay}</small>` : ''}
                <input type="hidden" name="cart[${index}][id]" value="${item.id}">
            </td>
            <td>
                ${item.hasDiscount ? 
                    `<small class="text-muted text-decoration-line-through d-block">Rp ${item.originalPrice.toLocaleString()}</small>` : 
                    ''
                }
                Rp ${item.price.toLocaleString()}
            </td>
            <td><input type="number" class="form-control" name="cart[${index}][qty]" value="${item.qty}" min="1" onchange="updateQty(${index}, this.value)"></td>
            <td>Rp ${subtotal.toLocaleString()}</td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm" onclick="removeItem(${index})">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
            <input type="hidden" name="cart[${index}][price]" value="${item.price}">
        </tr>`;
        });
    }

    // 3. FUNGSI PERHITUNGAN TOTAL
    function calculateTotal() {
        let subtotal = cart.reduce((sum, item) => sum + (item.qty * item.price), 0);
        let taxPercent = parseFloat(document.getElementById('tax').value) || 0;
        let discount = parseFloat(document.getElementById('discount').value) || 0;

        let taxAmount = (subtotal * taxPercent) / 100;
        let total = subtotal - discount + taxAmount;

        // Update tampilan
        document.getElementById('subtotal').value = 'Rp ' + subtotal.toLocaleString();
        document.getElementById('tax-amount').value = 'Rp ' + taxAmount.toLocaleString();
        document.getElementById('total').value = 'Rp ' + total.toLocaleString();

        // Simpan total untuk perhitungan kembalian
        document.getElementById('kasir-form').dataset.total = total;

        // Update kembalian jika sudah ada input bayar
        updateChange();
    }

    // 4. FUNGSI UPDATE QUANTITY
    function updateQty(index, qty) {
        cart[index].qty = parseInt(qty);
        renderCart();
        calculateTotal();
    }

    // 5. FUNGSI REMOVE ITEM
    function removeItem(index) {
        cart.splice(index, 1);
        renderCart();
        calculateTotal();
    }

    // 6. FUNGSI UPDATE CHANGE (KEMBALIAN)
    function updateChange() {
        let payment = parseFloat(document.getElementById('payment').value) || 0;
        let total = parseFloat(document.getElementById('kasir-form').dataset.total || 0);
        let change = payment - total;

        document.getElementById('change').value = change >= 0 ? 'Rp ' + change.toLocaleString() : 'Rp 0';
    }

    // 7. 🖨️ FUNGSI SUBMIT TRANSAKSI YANG SUDAH DIPERBAIKI (INILAH BAGIAN YANG ANDA TANYAKAN)
    document.getElementById('kasir-form').addEventListener('submit', async function(e) {
        e.preventDefault();

        // Validasi cart tidak kosong
        if (cart.length === 0) {
            showErrorNotification('Keranjang belanja kosong! Tambahkan produk terlebih dahulu.');
            return;
        }

        // Validasi pembayaran
        const payment = parseFloat(document.getElementById('payment').value) || 0;
        const total = parseFloat(document.getElementById('kasir-form').dataset.total || 0);

        if (payment < total) {
            showErrorNotification('Pembayaran kurang! Silakan masukkan jumlah yang cukup.');
            return;
        }

        const formData = new FormData(this);

        try {
            // Tampilkan loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Menyimpan...';
            submitBtn.disabled = true;

            const response = await fetch("{{ route('kasir.store') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: formData
            });

            const result = await response.json();

            // Reset loading state
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;

            if (result.success) {
                // 🎯 RESET FORM SEBELUM BUKA STRUK
                resetForm();

                // Tampilkan notifikasi sukses
                showSuccessNotification('Transaksi berhasil disimpan! Membuka struk...');

                // Buka popup struk setelah delay kecil
                setTimeout(() => {
                    const printWindow = window.open(`/kasir/struk/${result.transaction_id}`, 'Struk', 'width=400,height=600');
                    printWindow.focus();
                }, 1000);

            } else {
                showErrorNotification(result.message || "Terjadi kesalahan saat menyimpan transaksi!");
            }
        } catch (error) {
            console.error('Error:', error);
            showErrorNotification('Terjadi kesalahan jaringan!');

            // Reset loading state jika error
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="bi bi-check-circle"></i> Simpan Transaksi';
            submitBtn.disabled = false;
        }
    });

    // 8. FUNGSI RESET FORM
    function resetForm() {
        // Reset cart
        cart = [];

        // Reset tampilan cart
        renderCart();

        // Reset input form
        document.getElementById('tax').value = 0;
        document.getElementById('discount').value = 0;
        document.getElementById('payment').value = '';
        document.getElementById('change').value = '';

        // Reset perhitungan
        calculateTotal();

        // Focus ke search product untuk transaksi berikutnya
        document.getElementById('searchProduct').focus();

        // Clear search
        document.getElementById('searchProduct').value = '';

        // Tampilkan semua produk kembali
        document.querySelectorAll('#productList .product-item').forEach(product => {
            product.style.display = '';
        });
    }

    // 9. FUNGSI NOTIFIKASI
    function showSuccessNotification(message) {
        showNotification(message, 'success');
    }

    function showErrorNotification(message) {
        showNotification(message, 'danger');
    }

    function showNotification(message, type) {
        // Hapus notifikasi sebelumnya
        const existingNotification = document.querySelector('.alert-notification');
        if (existingNotification) {
            existingNotification.remove();
        }

        // Buat element notifikasi baru
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-notification alert-dismissible fade show`;
        notification.style.position = 'fixed';
        notification.style.top = '20px';
        notification.style.right = '20px';
        notification.style.zIndex = '9999';
        notification.style.minWidth = '300px';
        notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

        document.body.appendChild(notification);

        // Auto remove setelah 5 detik
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    // 10. EVENT LISTENERS UNTUK PAJAK, DISKON, DAN PEMBAYARAN
    document.getElementById('tax').addEventListener('input', calculateTotal);
    document.getElementById('discount').addEventListener('input', calculateTotal);
    document.getElementById('payment').addEventListener('input', updateChange);

    // 11. FUNGSI PENCARIAN PRODUK
    document.getElementById('searchProduct').addEventListener('keyup', function() {
        const searchText = this.value.toLowerCase();
        const products = document.querySelectorAll('#productList .product-item');

        products.forEach(product => {
            const name = product.querySelector('.product-name').textContent.toLowerCase();
            if (name.includes(searchText)) {
                product.style.display = '';
            } else {
                product.style.display = 'none';
            }
        });
    });

    // 12. INISIALISASI AWAL
    calculateTotal();
</script>

<style>
    #searchProduct {
        max-width: 400px;
    }

    .form-control[readonly] {
        background-color: #f8f9fa;
        font-weight: bold;
    }
</style>
@endsection