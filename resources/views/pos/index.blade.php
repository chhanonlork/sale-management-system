@extends('layouts.pos')

@section('content')
    <style>
        /* ១. ផ្ទៃខាងក្រោយ និង Font */
        body {
            background-color: #f4f6f9;
            font-family: 'Battambang', sans-serif;
        }

        /* ២. កាតផលិតផល */
        .product-card {
            border: none;
            border-radius: 15px;
            background: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
            overflow: hidden;
            position: relative;
            cursor: pointer;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
            border: 1px solid #11998e;
        }

        /* ប៊ូតុង Add to Cart */
        .btn-add-cart {
            background: linear-gradient(to right, #11998e, #38ef7d);
            border: none;
            color: white;
            font-size: 0.9rem;
            font-weight: bold;
            border-radius: 50px;
            padding: 8px 0;
            width: 100%;
            transition: 0.2s;
            margin-top: auto;
        }

        .btn-add-cart:hover {
            background: linear-gradient(to right, #0e857a, #2ecc71);
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(17, 153, 142, 0.4);
        }

        /* ៣. ផ្នែក Cart */
        .cart-section {
            border-radius: 15px;
            border: none;
            display: flex;
            flex-direction: column;
            height: 100%;
            overflow: hidden;
        }

        .cart-header {
            background: #343a40;
            color: white;
            padding: 15px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-checkout {
            background: linear-gradient(45deg, #FF416C, #FF4B2B);
            border: none;
            color: white;
            font-weight: bold;
            padding: 15px;
            border-radius: 10px;
            width: 100%;
            transition: 0.3s;
        }

        .btn-checkout:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 75, 43, 0.4);
        }

        /* Category Buttons */
        .btn-cat {
            border: 1px solid #ddd;
            background: white;
            color: #555;
            border-radius: 20px;
            padding: 6px 15px;
            font-size: 0.9rem;
            white-space: nowrap;
        }

        .btn-cat.active,
        .btn-cat:hover {
            background: #11998e;
            color: white;
            border-color: #11998e;
        }

        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        ::-webkit-scrollbar-thumb {
            background: #bbb;
            border-radius: 10px;
        }

        /* រចនាផ្ទាំង Checkout ឱ្យទំនើប */
        .checkout-modal-container {
            text-align: center;
            font-family: 'Battambang', sans-serif;
        }

        .checkout-icon-circle {
            width: 60px;
            height: 60px;
            background: #e0f7fa;
            color: #00acc1;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-size: 30px;
        }

        .checkout-total-box {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 15px;
            margin: 15px 0;
            border: 1px dashed #ced4da;
        }

        .total-usd {
            color: #28a745;
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
            display: block;
            margin-bottom: 5px;
        }

        .total-riel {
            color: #007bff;
            font-size: 1.1rem;
            font-weight: 600;
        }

        /* Style សម្រាប់ Payment Option Buttons */
        .btn-check:checked + .payment-option {
            background-color: #e8f5e9;
            border-color: #28a745;
            color: #28a745;
            box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.25);
        }
        .payment-option {
            transition: all 0.2s;
            border: 2px solid #eee;
        }
        .payment-option:hover {
            border-color: #ccc;
        }
    </style>

    <div class="row h-100 g-3">
        <div class="col-md-8 col-lg-8 h-100 pb-3">
            <div class="card h-100 shadow-sm border-0" style="border-radius: 15px; background: #fff;">
                <div class="card-header bg-white border-0 pt-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0"><i class="fas fa-th me-2"></i> បញ្ជីទំនិញ</h5>
                        <input type="text" id="searchProduct" class="form-control bg-light border-0 w-50 rounded-pill px-3"
                            placeholder="🔍 ស្វែងរកឈ្មោះ..." onkeyup="searchProduct()">
                    </div>
                    <div class="d-flex overflow-auto pb-2" style="gap: 8px;">
                        <button class="btn btn-cat active" onclick="filterCategory('all', this)">ទាំងអស់</button>
                        @foreach($categories as $category)
                            <button class="btn btn-cat"
                                onclick="filterCategory({{ $category->id }}, this)">{{ $category->name }}</button>
                        @endforeach
                    </div>
                </div>

                <div class="card-body overflow-auto bg-light">
                    <div class="row g-3" id="product-list">
                        @foreach($products as $product)
                            @php
                                $imgUrl = $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/400x400/png?text=No+Image';
                            @endphp

                            <div class="col-xl-3 col-lg-4 col-md-4 col-6 product-item"
                                data-category="{{ $product->category_id }}" data-name="{{ strtolower($product->name) }}">

                                <div class="card product-card h-100 p-2"
                                    onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->sale_price }}, {{ $product->qty }}, '{{ $imgUrl }}')">

                                    <div class="card-img-top-wrapper"
                                        style="height: 150px; overflow: hidden; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                        <img src="{{ $imgUrl }}" alt="{{ $product->name }}" class="img-fluid"
                                            style="max-height: 100%; max-width: 100%; object-fit: contain;"
                                            onerror="this.onerror=null; this.src='https://placehold.co/400x400/png?text=No+Image';">
                                    </div>

                                    <div class="text-center d-flex flex-column flex-grow-1 pt-2">
                                        <h6 class="fw-bold text-dark text-truncate mb-1" title="{{ $product->name }}">
                                            {{ $product->name }}
                                        </h6>
                                        <small class="text-muted mb-2">ស្តុក: {{ $product->qty }}</small>
                                        <button class="btn btn-add-cart">
                                            <i class="fas fa-cart-plus"></i> ដាក់កន្ត្រក
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-lg-4 h-100 pb-3">
            <div class="card cart-section shadow h-100">
                <div class="cart-header">
                    <span><i class="fas fa-shopping-cart me-2"></i> ការកុម្ម៉ង់</span>
                    <span class="badge bg-danger rounded-pill" id="cart-count">0</span>
                </div>

                <div class="card-body p-0 overflow-auto bg-white" style="flex: 1;">
                    <table class="table table-hover align-middle mb-0 small">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th class="ps-3">ទំនិញ</th>
                                <th class="text-center">ចំនួន</th>
                                <th class="text-end pe-3">តម្លៃសរុប</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="cart-items">
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">មិនទាន់មានទំនិញ</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="card-footer bg-white p-3 border-top">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">សរុបបណ្តោះអាសន្ន:</span> <span id="sub-total" class="fw-bold">0 ៛</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 align-items-center">
                        <h4 class="fw-bold text-dark m-0">សរុប:</h4>
                        <h3 id="final-total" class="fw-bold text-success m-0">0 ៛</h3>
                    </div>
                    <button class="btn btn-checkout" onclick="processCheckout()">
                        <i class="fas fa-money-bill-wave me-2"></i> គិតលុយ (Checkout)
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 1500, timerProgressBar: true });
        let cart = [];
        const EXCHANGE_RATE = 4000;

        function filterCategory(catId, btn) {
            $('.btn-cat').removeClass('active'); $(btn).addClass('active');
            $('.product-item').each(function () {
                var itemCat = $(this).data('category');
                (catId === 'all' || itemCat == catId) ? $(this).fadeIn(200) : $(this).hide();
            });
        }

        function searchProduct() {
            var value = $('#searchProduct').val().toLowerCase();
            $("#product-list .product-item").filter(function () {
                $(this).toggle($(this).data('name').indexOf(value) > -1)
            });
        }

        function addToCart(id, name, price, stock, image) {
            if (stock <= 0) { Toast.fire({ icon: 'error', title: 'អស់ស្តុកហើយ!' }); return; }
            let item = cart.find(i => i.id === id);
            if (item) {
                if (item.qty < stock) { item.qty++; Toast.fire({ icon: 'success', title: 'បានបន្ថែមចំនួន!' }); }
                else { Toast.fire({ icon: 'warning', title: 'ចំនួនលើសស្តុក!' }); }
            } else {
                cart.push({ id, name, price, qty: 1, image: image });
                Toast.fire({ icon: 'success', title: 'បានដាក់ចូលកន្ត្រក!' });
            }
            renderCart();
        }

        function formatRiel(amount) { return amount.toLocaleString('en-US'); }

        function renderCart() {
            let tbody = document.getElementById('cart-items'); tbody.innerHTML = '';
            let totalUSD = 0; let count = 0;

            if (cart.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-5">មិនទាន់មានទំនិញ</td></tr>';
                $('#sub-total, #final-total').text('0 ៛'); $('#cart-count').text(0); return;
            }

            cart.forEach((item, index) => {
                let itemTotalUSD = item.price * item.qty;
                let itemTotalRiel = itemTotalUSD * EXCHANGE_RATE;
                totalUSD += itemTotalUSD; count += item.qty;

                tbody.innerHTML += `
                                    <tr>
                                        <td class="ps-3">
                                            <div class="d-flex align-items-center">
                                                <img src="${item.image}" 
                                                     onerror="this.onerror=null; this.src='https://placehold.co/400x400/png?text=No+Image';"
                                                     class="rounded shadow-sm border me-2" width="40" height="40" style="object-fit: cover;">
                                                <div class="fw-bold text-truncate" style="max-width: 90px;" title="${item.name}">${item.name}</div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-secondary" onclick="updateQty(${index}, -1)">-</button>
                                                <button class="btn btn-light disabled text-dark" style="width:30px;">${item.qty}</button>
                                                <button class="btn btn-outline-secondary" onclick="updateQty(${index}, 1)">+</button>
                                            </div>
                                        </td>
                                        <td class="text-end fw-bold pe-3 text-primary">${formatRiel(itemTotalRiel)} ៛</td>
                                        <td class="text-end pe-2"><i class="fas fa-trash text-danger" style="cursor:pointer;" onclick="removeItem(${index})"></i></td>
                                    </tr>`;
            });

            let totalRiel = totalUSD * EXCHANGE_RATE;
            $('#sub-total, #final-total').text(formatRiel(totalRiel) + ' ៛');
            $('#cart-count').text(count);
        }

        function updateQty(index, change) {
            if (cart[index].qty + change > 0) {
                cart[index].qty += change;
            } else {
                cart.splice(index, 1);
            }
            renderCart();
        }

        function removeItem(index) {
            cart.splice(index, 1);
            renderCart();
        }

        // ============================================
        // ✅ កែប្រែ៖ មុខងារ Checkout ថ្មីជាមួយ Payment Method
        // ============================================
        function processCheckout() {
            if (cart.length === 0) {
                Swal.fire({ icon: 'warning', title: 'កន្ត្រកទទេ!', text: 'សូមជ្រើសរើសទំនិញជាមុនសិន', confirmButtonColor: '#ffc107'});
                return;
            }

            let totalUSD = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            let totalRiel = totalUSD * EXCHANGE_RATE;

            Swal.fire({
                title: '',
                html: `
                <div class="checkout-modal-container">
                    <div class="checkout-icon-circle">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <h3 class="fw-bold text-dark">បញ្ជាក់ការទូទាត់</h3>
                    <p class="text-muted small">សូមជ្រើសរើសវិធីបង់ប្រាក់</p>

                    <div class="checkout-total-box">
                        <span class="total-label text-muted small">សរុបទឹកប្រាក់ត្រូវបង់</span>
                        <span class="total-usd">$${totalUSD.toFixed(2)}</span>
                        <span class="total-riel">≈ ${formatRiel(totalRiel)} ៛</span>
                    </div>

                    <div class="mb-3 text-start">
                        <label class="form-label small fw-bold text-muted">វិធីបង់ប្រាក់:</label>
                        <div class="row g-2">
                            <div class="col-4">
                                <input type="radio" class="btn-check" name="payment_method" id="pay_cash" value="cash" checked>
                                <label class="btn btn-light w-100 py-2 payment-option" for="pay_cash">
                                    <i class="fas fa-money-bill-wave d-block mb-1 text-success"></i> <span class="small fw-bold">លុយសុទ្ធ</span>
                                </label>
                            </div>
                            <div class="col-4">
                                <input type="radio" class="btn-check" name="payment_method" id="pay_qr" value="qr">
                                <label class="btn btn-light w-100 py-2 payment-option" for="pay_qr">
                                    <i class="fas fa-qrcode d-block mb-1 text-primary"></i> <span class="small fw-bold">KHQR</span>
                                </label>
                            </div>
                            <div class="col-4">
                                <input type="radio" class="btn-check" name="payment_method" id="pay_card" value="card">
                                <label class="btn btn-light w-100 py-2 payment-option" for="pay_card">
                                    <i class="fas fa-credit-card d-block mb-1 text-warning"></i> <span class="small fw-bold">កាត</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div id="qr-display" class="d-none mb-3 p-3 bg-white rounded border text-center">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=example_payment" class="img-fluid mb-2" style="width:120px;">
                        <p class="small text-muted m-0">ស្កេនដើម្បីបង់ប្រាក់</p>
                    </div>

                    <div class="d-flex justify-content-between text-muted small px-2">
                        <span>ចំនួនមុខទំនិញ:</span>
                        <span class="fw-bold text-dark">${cart.length} មុខ</span>
                    </div>
                </div>
                `,
                showCancelButton: true,
                confirmButtonColor: '#11998e',
                cancelButtonColor: '#ff4b2b',
                confirmButtonText: '<i class="fas fa-print me-1"></i> ទទួល & បោះពុម្ព',
                cancelButtonText: 'បោះបង់',
                reverseButtons: true,
                width: 450,
                padding: '1.5rem',
                customClass: { popup: 'rounded-4 shadow-lg' },
                
                // Logic ពេល Modal បើក (ដើម្បី Handle ការចុចប្តូរ Payment)
                didOpen: () => {
                    const radios = Swal.getPopup().querySelectorAll('input[name="payment_method"]');
                    const qrDisplay = Swal.getPopup().querySelector('#qr-display');
                    
                    radios.forEach(radio => {
                        radio.addEventListener('change', (e) => {
                            if (e.target.value === 'qr') {
                                $(qrDisplay).removeClass('d-none').hide().fadeIn();
                            } else {
                                $(qrDisplay).addClass('d-none');
                            }
                        });
                    });
                },
                
                // Logic មុននឹងចុច Confirm (យកតម្លៃ Payment)
                preConfirm: () => {
                    const selectedMethod = Swal.getPopup().querySelector('input[name="payment_method"]:checked').value;
                    return selectedMethod;
                }

            }).then((result) => {
                if (result.isConfirmed) {
                    const paymentMethod = result.value; // ទទួលបានវិធីបង់ប្រាក់ (cash, qr, card)

                    Swal.fire({
                        title: 'កំពុងដំណើរការ...',
                        html: 'សូមរង់ចាំមួយភ្លែត',
                        timerProgressBar: true,
                        didOpen: () => { Swal.showLoading() }
                    });

                    $.ajax({
                        url: "{{ route('pos.checkout') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            items: cart,
                            total_amount: totalUSD,
                            payment_method: paymentMethod, // ✅ បញ្ជូនវិធីបង់ប្រាក់ទៅ Backend
                            customer_id: $('#customer_id').val() || 1
                        },
                        success: function (res) {
                            if (res.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'ជោគជ័យ!',
                                    text: 'ការលក់ត្រូវបានរក្សាទុក',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('បរាជ័យ', res.message, 'error');
                            }
                        },
                        error: function (err) {
                            console.log(err);
                            Swal.fire('Error', 'មានបញ្ហាក្នុងការភ្ជាប់ទៅ Server', 'error');
                        }
                    });
                }
            });
        }
    </script>
@endsection