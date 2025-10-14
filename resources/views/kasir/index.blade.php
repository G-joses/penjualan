@extends('layouts.app')

@section('title', 'Kasir')

@section('content')
<div class="container mt-4">
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form id="kasir-form" method="POST" action="{{ route('kasir.store') }}">
        @csrf
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

        <div class="form-group mb-2">
            <label>Total:</label>
            <input type="text" id="total" class="form-control" readonly>
        </div>
        <div class="form-group mb-2">
            <label>Bayar:</label>
            <input type="number" name="payment" id="payment" class="form-control">
        </div>
        <div class="form-group mb-3">
            <label>Kembalian:</label>
            <input type="text" id="change" class="form-control" readonly>
        </div>

        <button type="submit" class="btn btn-success">Simpan Transaksi</button>
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
                    <p>Rp {{ number_format($p->price, 0, ',', '.') }}</p>
                    <button type="button" class="btn btn-primary btn-add"
                        data-id="{{ $p->id }}"
                        data-name="{{ $p->name }}"
                        data-price="{{ $p->price }}">
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

    document.querySelectorAll('.btn-add').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const name = btn.dataset.name;
            const price = parseFloat(btn.dataset.price);
            const existing = cart.find(i => i.id === id);

            if (existing) {
                existing.qty++;
            } else {
                cart.push({
                    id,
                    name,
                    price,
                    qty: 1
                });
            }
            renderCart();
        });
    });

    function renderCart() {
        let tbody = document.getElementById('cart-body');
        tbody.innerHTML = '';
        let total = 0;

        cart.forEach((item, index) => {
            let subtotal = item.qty * item.price;
            total += subtotal;

            tbody.innerHTML += `
            <tr>
                <td>${item.name}<input type="hidden" name="cart[${index}][id]" value="${item.id}"></td>
                <td>Rp ${item.price.toLocaleString()}</td>
                <td><input type="number" class="form-control" name="cart[${index}][qty]" value="${item.qty}" min="1" data-index="${index}" onchange="updateQty(${index}, this.value)"></td>
                <td>Rp ${subtotal.toLocaleString()}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeItem(${index})">
                        Hapus
                    </button>
                </td>
                <input type="hidden" name="cart[${index}][price]" value="${item.price}">
            </tr>`;
        });

        document.getElementById('total').value = total.toLocaleString();
        document.getElementById('kasir-form').dataset.total = total;
    }

    function updateQty(index, qty) {
        cart[index].qty = parseInt(qty);
        renderCart();
    }

    // 🗑️ Fungsi untuk hapus barang dari cart
    function removeItem(index) {
        cart.splice(index, 1);
        renderCart();
    }

    // fungsi kembalian
    document.getElementById('payment').addEventListener('input', e => {
        let payment = parseFloat(e.target.value) || 0;
        let total = parseFloat(document.getElementById('kasir-form').dataset.total || 0);
        document.getElementById('change').value = (payment - total).toLocaleString();
    });

    // fungsi struk
    document.getElementById('kasir-form').addEventListener('submit', async function(e) {
        e.preventDefault(); // cegah reload halaman

        const formData = new FormData(this);

        const response = await fetch("{{ route('kasir.store') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            // buka popup struk otomatis
            const printWindow = window.open(`/kasir/struk/${result.transaction_id}`, 'Struk', 'width=400,height=600');
            printWindow.focus();
            // Tutup otomatis setelah print
            printWindow.onload = function() {
                printWindow.print();
                printWindow.onafterprint = function() {
                    printWindow.close();
                };
            };
            // Reset form dan keranjang
            cart = [];
            renderCart();
            document.getElementById('payment').value = '';
            document.getElementById('change').value = '';
        } else {
            alert("Terjadi kesalahan saat menyimpan transaksi!");
        };
    });

    // 🔍 Fitur pencarian produk
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
</script>
<style>
    #searchProduct {
        max-width: 400px;
    }
</style>
@endsection