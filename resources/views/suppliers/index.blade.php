@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center" style="border-radius: 15px 15px 0 0;">
            <h5 class="fw-bold mb-0 text-dark">
                <i class="fas fa-truck me-2 text-primary"></i>បញ្ជីអ្នកផ្គត់ផ្គង់
            </h5>
            <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-toggle="offcanvas" data-bs-target="#addSupplierOffcanvas">
                <i class="fas fa-plus-circle me-1"></i> បន្ថែមអ្នកផ្គត់ផ្គង់
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
                            <th class="ps-4">ID</th>
                            <th>ឈ្មោះក្រុមហ៊ុន</th>
                            <th>ឈ្មោះអ្នកទំនាក់ទំនង</th>
                            <th>លេខទូរស័ព្ទ</th>
                            <th>អាសយដ្ឋាន</th>
                            <th class="text-center">សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($suppliers as $supplier)
                            <tr>
                                <td class="ps-4 fw-bold text-secondary">#{{ $supplier->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                            <i class="fas fa-building"></i>
                                        </div>
                                        <span class="fw-bold">{{ $supplier->name }}</span>
                                    </div>
                                </td>
                                <td class="text-secondary fw-bold">{{ $supplier->contact_name ?? 'N/A' }}</td>
                                <td class="font-monospace text-dark">{{ $supplier->phone ?? '-' }}</td>
                                <td class="small">{{ Str::limit($supplier->address, 30) ?? '-' }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-sm btn-outline-primary border-0" onclick="editSupplier({{ json_encode($supplier) }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('លុបអ្នកផ្គត់ផ្គង់នេះ?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-3">{{ $suppliers->links() }}</div>
            </div>
        </div>
    </div>
</div>

{{-- ✅ 1. ADD SUPPLIER OFFCANVAS (2 Rows Layout) --}}
<div class="offcanvas offcanvas-end shadow-lg m-3 border-0" tabindex="-1" id="addSupplierOffcanvas" 
     style="width: 600px !important; height: fit-content !important; border-radius: 20px; top: 10px;">
    
    <div class="offcanvas-header bg-primary text-white py-3" style="border-top-left-radius: 20px; border-top-right-radius: 20px;">
        <h5 class="offcanvas-title fw-bold fs-6"><i class="fas fa-truck me-2"></i> បន្ថែមអ្នកផ្គត់ផ្គង់</h5>
        <button type="button" class="btn-close btn-close-white small" data-bs-dismiss="offcanvas"></button>
    </div>
    
    <div class="offcanvas-body bg-white p-4" style="border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">
        <form action="{{ route('suppliers.store') }}" method="POST">
            @csrf
            {{-- ជួរទី ១ --}}
            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label class="form-label fw-bold small text-secondary">ឈ្មោះក្រុមហ៊ុន <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control bg-light border-0 shadow-sm rounded-3 p-2" required>
                </div>
                <div class="col-6">
                    <label class="form-label fw-bold small text-secondary">ឈ្មោះអ្នកទំនាក់ទំនង</label>
                    <input type="text" name="contact_name" class="form-control bg-light border-0 shadow-sm rounded-3 p-2">
                </div>
            </div>

            {{-- ជួរទី ២ --}}
            <div class="row g-3 mb-4">
                <div class="col-6">
                    <label class="form-label fw-bold small text-secondary">លេខទូរស័ព្ទ</label>
                    <input type="text" name="phone" class="form-control bg-light border-0 shadow-sm rounded-3 p-2">
                </div>
                <div class="col-6">
                    <label class="form-label fw-bold small text-secondary">អាសយដ្ឋាន</label>
                    <textarea name="address" class="form-control bg-light border-0 shadow-sm rounded-3 p-2" rows="1" style="resize: none;"></textarea>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary shadow-sm fw-bold py-2 rounded-3">រក្សាទុក</button>
            </div>
        </form>
    </div>
</div>

{{-- ✅ 2. EDIT SUPPLIER OFFCANVAS (2 Rows Layout) --}}
<div class="offcanvas offcanvas-end shadow-lg m-3 border-0" tabindex="-1" id="editSupplierOffcanvas" 
     style="width: 600px !important; height: fit-content !important; border-radius: 20px; top: 10px;">
    
    <div class="offcanvas-header bg-warning text-dark py-3" style="border-top-left-radius: 20px; border-top-right-radius: 20px;">
        <h5 class="offcanvas-title fw-bold fs-6"><i class="fas fa-edit me-2"></i> កែប្រែអ្នកផ្គត់ផ្គង់</h5>
        <button type="button" class="btn-close small" data-bs-dismiss="offcanvas"></button>
    </div>
    
    <div class="offcanvas-body bg-white p-4" style="border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">
        <form id="editSupplierForm" method="POST">
            @csrf @method('PUT')
            {{-- ជួរទី ១ --}}
            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label class="form-label fw-bold small text-secondary">ឈ្មោះក្រុមហ៊ុន <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="edit_name" class="form-control bg-light border-0 shadow-sm rounded-3 p-2" required>
                </div>
                <div class="col-6">
                    <label class="form-label fw-bold small text-secondary">ឈ្មោះអ្នកទំនាក់ទំនង</label>
                    <input type="text" name="contact_name" id="edit_contact_name" class="form-control bg-light border-0 shadow-sm rounded-3 p-2">
                </div>
            </div>

            {{-- ជួរទី ២ --}}
            <div class="row g-3 mb-4">
                <div class="col-6">
                    <label class="form-label fw-bold small text-secondary">លេខទូរស័ព្ទ</label>
                    <input type="text" name="phone" id="edit_phone" class="form-control bg-light border-0 shadow-sm rounded-3 p-2">
                </div>
                <div class="col-6">
                    <label class="form-label fw-bold small text-secondary">អាសយដ្ឋាន</label>
                    <textarea name="address" id="edit_address" class="form-control bg-light border-0 shadow-sm rounded-3 p-2" rows="1" style="resize: none;"></textarea>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-warning shadow-sm fw-bold text-dark py-2 rounded-3">ធ្វើបច្ចុប្បន្នភាព</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editSupplier(supplier) {
        var bsOffcanvas = new bootstrap.Offcanvas(document.getElementById('editSupplierOffcanvas'));
        bsOffcanvas.show();

        document.getElementById('edit_name').value = supplier.name;
        document.getElementById('edit_contact_name').value = supplier.contact_name;
        document.getElementById('edit_phone').value = supplier.phone;
        document.getElementById('edit_address').value = supplier.address;

        var url = "{{ route('suppliers.update', ':id') }}";
        url = url.replace(':id', supplier.id);
        document.getElementById('editSupplierForm').action = url;
    }
</script>
@endsection