@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center" style="border-radius: 15px 15px 0 0;">
            <h5 class="fw-bold mb-0 text-dark">
                <i class="fas fa-boxes me-2 text-primary"></i>បញ្ជីទំនិញក្នុងស្តុក
            </h5>
            <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-toggle="offcanvas" data-bs-target="#addProductOffcanvas">
                <i class="fas fa-plus-circle me-1"></i> បន្ថែមទំនិញថ្មី
            </button>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-pill px-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">រូបភាព</th>
                            <th>ID</th>
                            <th>ឈ្មោះទំនិញ</th>
                            <th>បាកូដ</th>
                            <th>ប្រភេទ</th>
                            <th>អ្នកផ្គត់ផ្គង់</th>
                            <th>តម្លៃដើម</th>
                            <th>តម្លៃលក់</th>
                            <th>ស្តុក</th>
                            <th class="text-center">សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td class="ps-4">
                                    <div class="bg-light rounded border d-flex align-items-center justify-content-center overflow-hidden" style="width: 50px; height: 50px;">
                                        @if($product->image)
                                            <img src="{{ asset('storage/'.$product->image) }}" class="w-100 h-100 object-fit-cover">
                                        @else
                                            <i class="fas fa-image text-secondary opacity-25 fa-lg"></i>
                                        @endif
                                    </div>
                                </td>
                                <td class="fw-bold text-secondary">#{{ $product->id }}</td>
                                <td class="fw-bold">{{ $product->name }}</td>
                                <td class="text-primary font-monospace small">{{ $product->barcode ?? '-' }}</td>
                                <td>
                                    @if($product->category)
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info px-2 py-1 rounded-pill">
                                            {{ $product->category->name }}
                                        </span>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td class="text-dark small fw-bold">{{ $product->supplier->name ?? 'N/A' }}</td>
                                
                                {{-- ✅ កែមក cost_price --}}
                                <td class="text-muted">$ {{ number_format($product->cost_price, 2) }}</td>

                                {{-- ✅ កែមក sale_price --}}
                                <td class="fw-bold text-success fs-6">$ {{ number_format($product->sale_price, 2) }}</td>

                                <td>
                                    <span class="badge border {{ $product->qty < 10 ? 'bg-danger text-white' : 'bg-light text-dark border-secondary' }} px-3 py-2 rounded-pill">
                                        {{ $product->qty }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-sm btn-outline-warning border-0"
                                            onclick="editProduct({{ json_encode($product) }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger border-0"
                                                onclick="return confirm('តើអ្នកពិតជាចង់លុប {{ $product->name }} មែនទេ?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-3">{{ $products->links() }}</div>
            </div>
        </div>
    </div>
</div>

{{-- ✅ ADD FORM --}}
<div class="offcanvas offcanvas-end shadow-lg m-3 border-0" tabindex="-1" id="addProductOffcanvas" aria-labelledby="addProductLabel" style="width: 700px !important; height: fit-content !important; border-radius: 20px; top: 10px;">
    <div class="offcanvas-header bg-primary text-white py-3" style="border-top-left-radius: 20px; border-top-right-radius: 20px;">
        <h5 class="offcanvas-title fw-bold fs-6" id="addProductLabel"><i class="fas fa-box-open me-2"></i> បន្ថែមទំនិញថ្មី</h5>
        <button type="button" class="btn-close btn-close-white small" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body bg-white p-4" style="border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold text-secondary small">ឈ្មោះទំនិញ <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control bg-light border-0 shadow-sm rounded-3 p-2" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold text-secondary small">បាកូដ</label>
                    <input type="text" name="barcode" class="form-control bg-light border-0 shadow-sm rounded-3 p-2">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold text-secondary small">ប្រភេទទំនិញ</label>
                    <select name="category_id" class="form-select bg-light border-0 shadow-sm rounded-3 p-2">
                        <option value="">ជ្រើសរើស...</option>
                        @foreach($categories as $cat) <option value="{{ $cat->id }}">{{ $cat->name }}</option> @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold text-secondary small">អ្នកផ្គត់ផ្គង់</label>
                    <select name="supplier_id" class="form-select bg-light border-0 shadow-sm rounded-3 p-2">
                        <option value="">ជ្រើសរើស...</option>
                        @foreach($suppliers as $sup) <option value="{{ $sup->id }}">{{ $sup->name }}</option> @endforeach
                    </select>
                </div>
                
                {{-- ✅ កែ name="cost_price" --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold text-secondary small">តម្លៃដើម ($)</label>
                    <input type="number" step="0.01" name="cost_price" class="form-control bg-light border-0 shadow-sm rounded-3 p-2">
                </div>

                {{-- ✅ កែ name="sale_price" --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold text-secondary small">តម្លៃលក់ ($) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="sale_price" class="form-control bg-light border-0 shadow-sm rounded-3 p-2" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold text-secondary small">ចំនួនស្តុក</label>
                    <input type="number" name="qty" class="form-control bg-light border-0 shadow-sm rounded-3 p-2" value="0">
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold text-secondary small">រូបភាព</label>
                    <input type="file" name="image" class="form-control bg-light border-0 shadow-sm rounded-3 p-2">
                </div>
            </div>
            <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-primary shadow-sm fw-bold py-2 rounded-3">រក្សាទុក</button>
                <button type="button" class="btn btn-light border py-2 rounded-3" data-bs-dismiss="offcanvas">បោះបង់</button>
            </div>
        </form>
    </div>
</div>

{{-- ✅ EDIT FORM --}}
<div class="offcanvas offcanvas-end shadow-lg m-3 border-0" tabindex="-1" id="editProductOffcanvas" aria-labelledby="editProductLabel" style="width: 700px !important; height: fit-content !important; border-radius: 20px; top: 10px;">
    <div class="offcanvas-header bg-warning text-dark py-3" style="border-top-left-radius: 20px; border-top-right-radius: 20px;">
        <h5 class="offcanvas-title fw-bold fs-6" id="editProductLabel"><i class="fas fa-edit me-2"></i> កែប្រែព័ត៌មានទំនិញ</h5>
        <button type="button" class="btn-close small" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body bg-white p-4" style="border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">
        <form id="editProductForm" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold text-secondary small">ឈ្មោះទំនិញ <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="edit_name" class="form-control bg-light border-0 shadow-sm rounded-3 p-2" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold text-secondary small">បាកូដ</label>
                    <input type="text" name="barcode" id="edit_barcode" class="form-control bg-light border-0 shadow-sm rounded-3 p-2">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold text-secondary small">ប្រភេទទំនិញ</label>
                    <select name="category_id" id="edit_category" class="form-select bg-light border-0 shadow-sm rounded-3 p-2">
                        @foreach($categories as $cat) <option value="{{ $cat->id }}">{{ $cat->name }}</option> @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold text-secondary small">អ្នកផ្គត់ផ្គង់</label>
                    <select name="supplier_id" id="edit_supplier" class="form-select bg-light border-0 shadow-sm rounded-3 p-2">
                        <option value="">ជ្រើសរើស...</option>
                        @foreach($suppliers as $sup) <option value="{{ $sup->id }}">{{ $sup->name }}</option> @endforeach
                    </select>
                </div>

                {{-- ✅ កែ name="cost_price" --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold text-secondary small">តម្លៃដើម ($)</label>
                    <input type="number" step="0.01" name="cost_price" id="edit_cost_price" class="form-control bg-light border-0 shadow-sm rounded-3 p-2">
                </div>

                {{-- ✅ កែ name="sale_price" --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold text-secondary small">តម្លៃលក់ ($) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="sale_price" id="edit_sale_price" class="form-control bg-light border-0 shadow-sm rounded-3 p-2" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold text-secondary small">ចំនួនស្តុក</label>
                    <input type="number" name="qty" id="edit_qty" class="form-control bg-light border-0 shadow-sm rounded-3 p-2">
                </div>
                <div class="col-12">
                     <label class="form-label fw-bold text-secondary small">ប្តូររូបភាព</label>
                     <input type="file" name="image" class="form-control bg-light border-0 shadow-sm rounded-3 p-2">
                </div>
            </div>
            <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-warning shadow-sm fw-bold text-dark py-2 rounded-3">ធ្វើបច្ចុប្បន្នភាព</button>
                <button type="button" class="btn btn-light border py-2 rounded-3" data-bs-dismiss="offcanvas">បោះបង់</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editProduct(product) {
        var myOffcanvas = document.getElementById('editProductOffcanvas');
        var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas);
        bsOffcanvas.show();

        document.getElementById('edit_name').value = product.name;
        document.getElementById('edit_barcode').value = product.barcode;
        document.getElementById('edit_category').value = product.category_id;
        document.getElementById('edit_supplier').value = product.supplier_id;
        
        // ✅ ដាក់តម្លៃឱ្យត្រូវតាម Database
        document.getElementById('edit_cost_price').value = product.cost_price;
        document.getElementById('edit_sale_price').value = product.sale_price;
        
        document.getElementById('edit_qty').value = product.qty;

        var url = "{{ route('products.update', ':id') }}";
        url = url.replace(':id', product.id);
        document.getElementById('editProductForm').action = url;
    }
</script>
@endsection