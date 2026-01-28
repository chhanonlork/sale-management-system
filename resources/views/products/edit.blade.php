@extends('layouts.app')

@section('content')
    <style>
        /* Digital Modern UI Styling */
        :root {
            --digital-blue: #007bff;
            --digital-green: #28a745;
            --digital-bg: #f8f9fa;
            --digital-card-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        body {
            background-color: var(--digital-bg);
        }

        .card-edit-digital {
            border: none;
            border-radius: 20px;
            box-shadow: var(--digital-card-shadow);
            background: #fff;
        }

        .card-header-digital {
            background: #fff;
            border-bottom: 1px solid #edf2f9;
            padding: 20px 30px;
            border-radius: 20px 20px 0 0 !important;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }

        .form-control-digital,
        .form-select-digital {
            border-radius: 12px;
            padding: 12px 18px;
            border: 1px solid #e3ebf6;
            background-color: #f9fbfd;
            transition: 0.3s ease;
        }

        .form-control-digital:focus {
            border-color: var(--digital-blue);
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.1);
            background-color: #fff;
        }

        /* រចនាប័ទ្មតម្លៃលក់ពណ៌បៃតង */
        .sale-price-input {
            color: var(--digital-green) !important;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .btn-update-digital {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            border: none;
            color: white;
            padding: 12px 35px;
            border-radius: 50px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
        }

        .btn-cancel-digital {
            background: #f1f4f8;
            color: #6e84a3;
            padding: 12px 35px;
            border-radius: 50px;
            font-weight: 600;
            border: 1px solid #e3ebf6;
        }

        .current-img-preview {
            width: 100px;
            height: 100px;
            border-radius: 15px;
            border: 2px solid #edf2f9;
            object-fit: cover;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }
    </style>

    <div class="container py-4">
        <div class="card card-edit-digital">
            <div class="card-header-digital">
                <h5 class="mb-0 text-dark fw-bold">
                    <i class="fas fa-edit text-warning me-2"></i> កែប្រែទិន្នន័យទំនិញ
                </h5>
            </div>
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">ឈ្មោះទំនិញ</label>
                            <input type="text" name="name" class="form-control-digital form-control w-100"
                                value="{{ $product->name }}" placeholder="ឧទាហរណ៍៖ Coca Cola">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">បាកូដ (Barcode)</label>
                            <input type="text" name="barcode" class="form-control-digital form-control w-100"
                                value="{{ $product->barcode }}" placeholder="ស្កេនបាកូដ">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">ប្រភេទទំនិញ</label>
                            <select name="category_id" class="form-select-digital form-select w-100">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-secondary small">អ្នកផ្គត់ផ្គង់</label>
                            {{-- សំខាន់ត្រូវមាន id="edit_supplier" --}}
                            <select name="supplier_id" id="edit_supplier"
                                class="form-select bg-light border-0 shadow-sm rounded-3 p-2">
                                <option value="">ជ្រើសរើសអ្នកផ្គត់ផ្គង់...</option>

                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach

                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">តម្លៃដើម ($)</label>
                            <input type="number" step="0.01" name="cost_price"
                                class="form-control-digital form-control w-100" value="{{ $product->cost_price }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">តម្លៃលក់ ($)</label>
                            <input type="number" step="0.01" name="sale_price"
                                class="form-control-digital form-control w-100 sale-price-input"
                                value="{{ $product->sale_price }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">ចំនួនក្នុងស្តុក</label>
                            <input type="number" name="qty" class="form-control-digital form-control w-100 text-center"
                                value="{{ $product->qty }}">
                        </div>

                        <div class="col-md-12 border-top pt-4">
                            <label class="form-label">រូបភាពទំនិញ (ទុកទំនេរបើមិនចង់ប្តូរ)</label>
                            <input type="file" name="image" class="form-control form-control-digital mb-3">

                            <div class="d-flex align-items-center bg-light p-3 rounded-3" style="width: fit-content;">
                                <div class="me-3 text-muted small fw-bold">រូបភាពបច្ចុប្បន្ន៖</div>
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" class="current-img-preview shadow-sm"
                                        onerror="this.src='https://via.placeholder.com/100'">
                                @else
                                    <div class="bg-white d-flex align-items-center justify-content-center rounded-3 shadow-sm"
                                        style="width: 100px; height: 100px;">
                                        <i class="fas fa-image fa-2x text-light"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 pt-4 border-top d-flex justify-content-start">
                        <button type="submit" class="btn btn-update-digital me-3">
                            <i class="fas fa-check-circle me-2"></i> រក្សាទុកការកែប្រែ
                        </button>
                        <a href="{{ route('products.index') }}" class="btn btn-cancel-digital text-decoration-none">
                            <i class="fas fa-times-circle me-2"></i> បោះបង់
                            </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function editProduct(product) {
            var myOffcanvas = document.getElementById('editProductOffcanvas');
            var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas);
            bsOffcanvas.show();

            // បំពេញទិន្នន័យ
            document.getElementById('edit_name').value = product.name;
            document.getElementById('edit_barcode').value = product.barcode;
            document.getElementById('edit_category').value = product.category_id;

            // ✅ បន្ថែមបន្ទាត់នេះ ដើម្បីឱ្យវាជ្រើសរើសអ្នកផ្គត់ផ្គង់តាមទិន្នន័យចាស់
            document.getElementById('edit_supplier').value = product.supplier_id;

            document.getElementById('edit_cost').value = product.cost;
            document.getElementById('edit_price').value = product.price;
            document.getElementById('edit_qty').value = product.qty;

            var url = "{{ route('products.update', ':id') }}";
            url = url.replace(':id', product.id);
            document.getElementById('editProductForm').action = url;
        }
    </script>
@endsection