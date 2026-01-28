@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center"
                style="border-radius: 15px 15px 0 0;">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="fas fa-tags me-2 text-primary"></i>គ្រប់គ្រងប្រភេទទំនិញ
                </h5>
                {{-- ✅ 1. ប៊ូតុង Add ប្រើ Offcanvas --}}
                <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-toggle="offcanvas"
                    data-bs-target="#addCategoryOffcanvas">
                    <i class="fas fa-plus-circle me-1"></i> បន្ថែមប្រភេទទំនិញ
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
                                <th>ឈ្មោះប្រភេទទំនិញ</th>
                                <th class="text-center">សកម្មភាព</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                                <tr>
                                    <td class="ps-4 fw-bold text-secondary">#{{ $category->id }}</td>
                                    <td class="fw-bold">{{ $category->name }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            {{-- ប៊ូតុង Edit --}}
                                            <button class="btn btn-sm btn-outline-warning border-0"
                                                onclick="editCategory({{ $category->id }}, '{{ $category->name }}')">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            {{-- ប៊ូតុង Delete --}}
                                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger border-0"
                                                    onclick="return confirm('តើអ្នកពិតជាចង់លុបប្រភេទ {{ $category->name }} នេះមែនទេ?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-3">{{ $categories->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== ✅ 2. ADD OFFCANVAS (Floating Box Design) ===================== --}}
    <div class="offcanvas offcanvas-end border-0 shadow-lg mt-3 me-3" tabindex="-1" id="addCategoryOffcanvas"
        aria-labelledby="addCategoryLabel" style="width: 400px !important; height: fit-content; border-radius: 20px;">

        {{-- Header ពណ៌ខៀវ --}}
        <div class="offcanvas-header bg-primary text-white py-3"
            style="border-top-left-radius: 20px; border-top-right-radius: 20px;">
            <h5 class="offcanvas-title fw-bold fs-6" id="addCategoryLabel">
                <i class="fas fa-plus-circle me-2"></i> បន្ថែមប្រភេទទំនិញថ្មី
            </h5>
            <button type="button" class="btn-close btn-close-white small" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>

        {{-- Body --}}
        <div class="offcanvas-body bg-white p-4" style="border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="form-label fw-bold text-secondary small">ឈ្មោះប្រភេទទំនិញ</label>
                    <input type="text" name="name" class="form-control bg-light border-0 shadow-sm"
                        placeholder="បញ្ចូលឈ្មោះ..." style="border-radius: 8px; padding: 12px;" required>
                </div>

                {{-- ប៊ូតុង --}}
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary shadow-sm fw-bold py-2" style="border-radius: 10px;">
                        <i class="fas fa-save me-1"></i> រក្សាទុក
                    </button>
                    <button type="button" class="btn btn-light border py-2" data-bs-dismiss="offcanvas"
                        style="border-radius: 10px;">
                        បោះបង់
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===================== ✅ 3. EDIT OFFCANVAS (Floating Box Design) ===================== --}}
    <div class="offcanvas offcanvas-end border-0 shadow-lg mt-3 me-3" tabindex="-1" id="editCategoryOffcanvas"
        aria-labelledby="editCategoryLabel" style="width: 400px !important; height: fit-content; border-radius: 20px;">

        {{-- Header ពណ៌លឿង (ដូចរូបភាព) --}}
        <div class="offcanvas-header bg-warning text-dark py-3"
            style="border-top-left-radius: 20px; border-top-right-radius: 20px;">
            <h5 class="offcanvas-title fw-bold fs-6" id="editCategoryLabel">
                <i class="fas fa-edit me-2"></i> កែប្រែទិន្នន័យ
            </h5>
            <button type="button" class="btn-close small" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        {{-- Body --}}
        <div class="offcanvas-body bg-white p-4" style="border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">
            <form id="editCategoryForm" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="form-label fw-bold text-secondary small">ឈ្មោះប្រភេទទំនិញ</label>
                    <input type="text" name="name" id="edit_name" class="form-control bg-light border-0 shadow-sm"
                        style="border-radius: 8px; padding: 12px;" required>
                </div>

                {{-- ប៊ូតុង --}}
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-warning shadow-sm fw-bold text-dark py-2"
                        style="border-radius: 10px;">
                        <i class="fas fa-sync-alt me-1"></i> ធ្វើបច្ចុប្បន្នភាព
                    </button>
                    <button type="button" class="btn btn-light border py-2" data-bs-dismiss="offcanvas"
                        style="border-radius: 10px;">
                        បោះបង់
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    {{-- ===================== ✅ 4. JAVASCRIPT ===================== --}}
    <script>
        function editCategory(id, name) {
            // 1. រក Offcanvas Element
            var myOffcanvas = document.getElementById('editCategoryOffcanvas');
            var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas);

            // 2. បង្ហាញ Offcanvas
            bsOffcanvas.show();

            // 3. ដាក់ទិន្នន័យចូល Input
            document.getElementById('edit_name').value = name;

            // 4. Update Form Action URL
            var url = "{{ route('categories.update', ':id') }}";
            url = url.replace(':id', id);

            document.getElementById('editCategoryForm').action = url;
        }
    </script>

@endsection