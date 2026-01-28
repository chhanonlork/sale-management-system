@extends('layouts.app')
<style>
    /* Digital Style Colors */
    :root {
        --digital-blue: #007bff;
        --digital-green: #28a745;
        --digital-bg: #f8f9fa;
    }

    /* រចនា Card និងតារាង */
    .card-employee {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        background: #fff;
    }

    .table thead th {
        background-color: #f9fbfd;
        color: #6e84a3;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
    }

    /* Badge សម្រាប់តួនាទី */
    .badge-position {
        background-color: #e3f2fd;
        color: #09160c;
        padding: 5px 15px;
        border-radius: 20px;
        font-weight: 500;
    }
</style>

@section('content')
    <div class="container-fluid py-4">
        <div class="card shadow-sm border-0" style="border-radius: 15px;">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="fas fa-users-cog me-2 text-primary"></i>បញ្ជីបុគ្គលិក
                </h5>
                <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal"
                    data-bs-target="#addEmployeeModal">
                    <i class="fas fa-plus-circle me-1"></i> បន្ថែមបុគ្គលិកថ្មី
                </button>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">ID</th>
                                <th style="min-width: 150px;">ឈ្មោះបុគ្គលិក</th>
                                <th>តួនាទី</th>
                                <th class="text-end pe-4" style="min-width: 120px;">ប្រាក់ខែគោល</th>
                                <th class="text-center px-4" style="min-width: 140px;">លេខទូរស័ព្ទ</th>
                                <th class="ps-4">អ៊ីមែល</th>
                                <th>ថ្ងៃចូលធ្វើការ</th>
                                <th class="text-center">សកម្មភាព</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $employee)
                                <tr>
                                    <td class="ps-4 fw-bold text-primary">#{{ $employee->id }}</td>
                                    <td class="fw-bold text-dark">{{ $employee->name }}</td>
                                    <td>
                                        <span class="badge bg-soft-info text-info rounded-pill px-3">
                                            {{ $employee->position }}
                                        </span>
                                    </td>
                                    <td class="text-end fw-bold text-success pe-4">
                                        ${{ number_format($employee->salary, 2) }}
                                    </td>
                                    <td class="text-center px-4">
                                        <div class="d-inline-flex align-items-center bg-light rounded-pill px-3 py-1 border">
                                            <i class="fas fa-phone-alt text-secondary me-2 small"></i>
                                            <span class="fw-bold text-dark">{{ $employee->phone }}</span>
                                        </div>
                                    </td>
                                    <td class="ps-4">
                                        <span class="text-muted small">{{ $employee->email }}</span>
                                    </td>
                                    <td class="small">{{ $employee->start_date }}</td>
                                    <td class="text-center">
                                        <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                                            <button type="button" class="btn btn-sm btn-white text-warning border-end bg-white"
                                                onclick="editEmployee({{ json_encode($employee) }})" title="កែប្រែ">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('employees.destroy', $employee->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('តើបងពិតជាចង់លុបឈ្មោះ {{ $employee->name }} មែនទេ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-white text-danger bg-white"
                                                    title="លុប">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered ms-auto me-4" style="max-width: 600px;">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">

                <div class="modal-header bg-primary text-white p-4"
                    style="border-top-left-radius: 20px; border-top-right-radius: 20px;">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-user-plus me-2"></i>បន្ថែមបុគ្គលិកថ្មី
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4 bg-white">
                    <form action="{{ route('employees.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">

                            <div class="col-6">
                                <label class="fw-bold small text-muted mb-1">ឈ្មោះបុគ្គលិក</label>
                                <input type="text" name="name" class="form-control rounded-3 bg-light p-2"
                                    placeholder="ឈ្មោះពេញ" required>
                            </div>
                            <div class="col-6">
                                <label class="fw-bold small text-muted mb-1">តួនាទី</label>
                                <select name="position" class="form-select rounded-3 bg-light p-2" required>
                                    <option value="" selected disabled>-- ជ្រើសរើស --</option>
                                    <option value="Admin">Admin</option>
                                    <option value="General Manager">General Manager</option>
                                    <option value="Sale">Sale</option>
                                    <option value="Accountant">Accountant</option>
                                    <option value="Stock Keeper">Stock Keeper</option>
                                    <option value="Security">Security</option>
                                    <option value="Cleaner">Cleaner</option>
                                </select>
                            </div>

                            <div class="col-6">
                                <label class="fw-bold small text-muted mb-1">លេខទូរស័ព្ទ</label>
                                <input type="text" name="phone" class="form-control rounded-3 bg-light p-2"
                                    placeholder="0123456789" required>
                            </div>
                            <div class="col-6">
                                <label class="fw-bold small text-muted mb-1">អ៊ីមែល (Email)</label>
                                <input type="email" name="email" class="form-control rounded-3 bg-light p-2"
                                    placeholder="example@mail.com">
                            </div>

                            <div class="col-6">
                                <label class="fw-bold small text-muted mb-1">ប្រាក់ខែ ($)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-success fw-bold">$</span>
                                    <input type="number" step="0.01" name="salary"
                                        class="form-control rounded-end bg-light p-2 border-start-0" placeholder="0.00"
                                        required>
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="fw-bold small text-muted mb-1">ថ្ងៃចូលធ្វើការ</label>
                                <input type="date" name="start_date" class="form-control rounded-3 bg-light p-2" required>
                            </div>

                        </div>

                        <div class="mt-4 pt-2 d-grid gap-2">
                            <button type="submit" class="btn btn-primary rounded-pill py-2 fw-bold shadow-sm">
                                <i class="fas fa-save me-1"></i> រក្សាទុក
                            </button>
                            <button type="button" class="btn btn-light rounded-pill py-2 text-muted border"
                                data-bs-dismiss="modal">
                                បោះបង់
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered ms-auto me-4" style="max-width: 600px;">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">

                <div class="modal-header bg-warning bg-opacity-10 border-bottom-0 p-4"
                    style="border-top-left-radius: 20px; border-top-right-radius: 20px;">
                    <h5 class="modal-title fw-bold text-dark">
                        <i class="fas fa-edit text-warning me-2"></i>កែប្រែព័ត៌មានបុគ្គលិក
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4 bg-white">
                        <div class="row g-3">

                            <div class="col-6">
                                <label class="fw-bold small text-muted mb-1">ឈ្មោះបុគ្គលិក</label>
                                <input type="text" name="name" id="edit_name" class="form-control rounded-3 bg-light p-2"
                                    required>
                            </div>
                            <div class="col-6">
                                <label class="fw-bold small text-muted mb-1">តួនាទី</label>
                                <select name="position" id="edit_position" class="form-select rounded-3 bg-light p-2"
                                    required>
                                    <option value="Admin">Admin</option>
                                    <option value="General Manager">General Manager</option>
                                    <option value="Accountant">Accountant</option>
                                    <option value="Sale">Sale</option>
                                    <option value="Stock Keeper">Stock Keeper</option>
                                    <option value="Security">Security</option>
                                    <option value="Cleaner">Cleaner</option>
                                </select>
                            </div>

                            <div class="col-6">
                                <label class="fw-bold small text-muted mb-1">លេខទូរស័ព្ទ</label>
                                <input type="text" name="phone" id="edit_phone" class="form-control rounded-3 bg-light p-2"
                                    required>
                            </div>
                            <div class="col-6">
                                <label class="fw-bold small text-muted mb-1">អ៊ីមែល (Email)</label>
                                <input type="email" name="email" id="edit_email"
                                    class="form-control rounded-3 bg-light p-2">
                            </div>

                            <div class="col-6">
                                <label class="fw-bold small text-muted mb-1">ប្រាក់ខែ ($)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 fw-bold text-success">$</span>
                                    <input type="number" step="0.01" name="salary" id="edit_salary"
                                        class="form-control rounded-end bg-light p-2 border-start-0" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="fw-bold small text-muted mb-1">ថ្ងៃចូលធ្វើការ</label>
                                <input type="date" name="start_date" id="edit_start_date"
                                    class="form-control rounded-3 bg-light p-2" required>
                            </div>

                        </div>

                        <div class="mt-4 pt-2 d-grid gap-2">
                            <button type="submit" class="btn btn-warning rounded-pill py-2 fw-bold text-dark shadow-sm">
                                <i class="fas fa-save me-1"></i> រក្សាទុកការកែប្រែ
                            </button>
                            <button type="button" class="btn btn-light rounded-pill py-2 text-muted border"
                                data-bs-dismiss="modal">
                                បោះបង់
                            </button>
                        </div>
                </form>
            </div>
        </div>
    </div>
    </div>

    <script>
        function editEmployee(employee) {
            document.getElementById('edit_name').value = employee.name;
            document.getElementById('edit_position').value = employee.position;
            document.getElementById('edit_phone').value = employee.phone;
            document.getElementById('edit_email').value = employee.email;
            document.getElementById('edit_salary').value = employee.salary;
            document.getElementById('edit_start_date').value = employee.start_date;

            let form = document.getElementById('editForm');
            form.action = '/employees/' + employee.id;

            var myModal = new bootstrap.Modal(document.getElementById('editEmployeeModal'));
            myModal.show();
        }
    </script>
@endsection