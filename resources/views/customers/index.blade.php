@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center" style="border-radius: 15px 15px 0 0;">
            <h5 class="fw-bold mb-0 text-dark">
                <i class="fas fa-users me-2 text-primary"></i>បញ្ជីអតិថិជន
            </h5>
            <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-toggle="offcanvas" data-bs-target="#addCustomerOffcanvas">
                <i class="fas fa-user-plus me-1"></i> បន្ថែមអតិថិជន
            </button>
        </div>

        <div class="card-body">
            {{-- សារ Success --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-pill px-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- សារ Error --}}
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-pill px-4" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>ឈ្មោះអតិថិជន</th>
                            <th>លេខទូរស័ព្ទ</th>
                            <th>អ៊ីមែល</th> {{-- ✅ បង្ហាញ Email --}}
                            <th>អាសយដ្ឋាន</th>
                            <th class="text-center">សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                            <tr>
                                <td class="ps-4 fw-bold text-secondary">#{{ $customer->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <span class="fw-bold">{{ $customer->name }}</span>
                                    </div>
                                </td>
                                <td class="font-monospace text-dark">{{ $customer->phone ?? '-' }}</td>
                                
                                {{-- ✅ ទិន្នន័យ Email --}}
                                <td class="text-primary small">{{ $customer->email ?? '-' }}</td>

                                <td class="small">{{ Str::limit($customer->address, 30) ?? '-' }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-sm btn-outline-primary border-0" onclick="editCustomer({{ json_encode($customer) }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('customers.destroy', $customer->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('តើអ្នកពិតជាចង់លុបអតិថិជននេះមែនទេ?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-3">{{ $customers->links() }}</div>
            </div>
        </div>
    </div>
</div>

{{-- ✅ 1. ADD CUSTOMER OFFCANVAS --}}
<div class="offcanvas offcanvas-end shadow-lg m-3 border-0" tabindex="-1" id="addCustomerOffcanvas" 
     style="width: 600px !important; height: fit-content !important; border-radius: 20px; top: 10px;">
    
    <div class="offcanvas-header bg-primary text-white py-3" style="border-top-left-radius: 20px; border-top-right-radius: 20px;">
        <h5 class="offcanvas-title fw-bold fs-6"><i class="fas fa-user-plus me-2"></i> បន្ថែមអតិថិជន</h5>
        <button type="button" class="btn-close btn-close-white small" data-bs-dismiss="offcanvas"></button>
    </div>
    
    <div class="offcanvas-body bg-white p-4" style="border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">
        <form action="{{ route('customers.store') }}" method="POST">
            @csrf
            {{-- ជួរទី ១ --}}
            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label class="form-label fw-bold small text-secondary">ឈ្មោះអតិថិជន <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control bg-light border-0 shadow-sm rounded-3 p-2" required>
                </div>
                <div class="col-6">
                    <label class="form-label fw-bold small text-secondary">លេខទូរស័ព្ទ</label>
                    <input type="text" name="phone" class="form-control bg-light border-0 shadow-sm rounded-3 p-2">
                </div>
            </div>

            {{-- ជួរទី ២ --}}
            <div class="row g-3 mb-4">
                <div class="col-6">
                    <label class="form-label fw-bold small text-secondary">អ៊ីមែល (Email)</label>
                    <input type="email" name="email" class="form-control bg-light border-0 shadow-sm rounded-3 p-2">
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

{{-- ✅ 2. EDIT CUSTOMER OFFCANVAS --}}
<div class="offcanvas offcanvas-end shadow-lg m-3 border-0" tabindex="-1" id="editCustomerOffcanvas" 
     style="width: 600px !important; height: fit-content !important; border-radius: 20px; top: 10px;">
    
    <div class="offcanvas-header bg-warning text-dark py-3" style="border-top-left-radius: 20px; border-top-right-radius: 20px;">
        <h5 class="offcanvas-title fw-bold fs-6"><i class="fas fa-user-edit me-2"></i> កែប្រែអតិថិជន</h5>
        <button type="button" class="btn-close small" data-bs-dismiss="offcanvas"></button>
    </div>
    
    <div class="offcanvas-body bg-white p-4" style="border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">
        <form id="editCustomerForm" method="POST">
            @csrf @method('PUT')
            {{-- ជួរទី ១ --}}
            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label class="form-label fw-bold small text-secondary">ឈ្មោះអតិថិជន <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="edit_name" class="form-control bg-light border-0 shadow-sm rounded-3 p-2" required>
                </div>
                <div class="col-6">
                    <label class="form-label fw-bold small text-secondary">លេខទូរស័ព្ទ</label>
                    <input type="text" name="phone" id="edit_phone" class="form-control bg-light border-0 shadow-sm rounded-3 p-2">
                </div>
            </div>

            {{-- ជួរទី ២ --}}
            <div class="row g-3 mb-4">
                <div class="col-6">
                    <label class="form-label fw-bold small text-secondary">អ៊ីមែល (Email)</label>
                    <input type="email" name="email" id="edit_email" class="form-control bg-light border-0 shadow-sm rounded-3 p-2">
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
    function editCustomer(customer) {
        var bsOffcanvas = new bootstrap.Offcanvas(document.getElementById('editCustomerOffcanvas'));
        bsOffcanvas.show();

        document.getElementById('edit_name').value = customer.name;
        document.getElementById('edit_phone').value = customer.phone;
        document.getElementById('edit_email').value = customer.email; // ✅ បង្ហាញ Email ពេល Edit
        document.getElementById('edit_address').value = customer.address;

        var url = "{{ route('customers.update', ':id') }}";
        url = url.replace(':id', customer.id);
        document.getElementById('editCustomerForm').action = url;
    }
</script>
@endsection