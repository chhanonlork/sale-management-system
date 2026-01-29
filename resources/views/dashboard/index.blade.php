@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">Dashboard</h2>
        <div class="text-secondary">
             <i class="fas fa-clock me-2"></i>{{ \Carbon\Carbon::now()->format('l, d F Y') }}
        </div>
    </div>

    <div class="row g-3 mb-4">
        
        {{-- Card 1: TOTAL SALES --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 p-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-primary fw-bold small text-uppercase" style="letter-spacing: 0.5px;">Total Sales (Today)</h6>
                        <h3 class="fw-bold text-dark mb-0">${{ number_format($todaySales, 2) }}</h3>
                    </div>
                    <div class="rounded p-3 bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-calendar-check fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: TOTAL PRODUCTS --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 p-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-success fw-bold small text-uppercase" style="letter-spacing: 0.5px;">Total Products</h6>
                        <h3 class="fw-bold text-dark mb-0">{{ $totalProducts }}</h3>
                    </div>
                    <div class="rounded p-3 bg-success bg-opacity-10 text-success">
                        <i class="fas fa-box-open fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: EMPLOYEES --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 p-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-info fw-bold small text-uppercase" style="letter-spacing: 0.5px;">Employees</h6>
                        <h3 class="fw-bold text-dark mb-0">{{ $totalEmployees }}</h3>
                    </div>
                    <div class="rounded p-3 bg-info bg-opacity-10 text-info">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 4: LOW STOCK --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 p-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-warning fw-bold small text-uppercase" style="letter-spacing: 0.5px;">Low Stock Items</h6>
                        <h3 class="fw-bold text-dark mb-0">{{ $lowStockItems }}</h3>
                    </div>
                    <div class="rounded p-3 bg-warning bg-opacity-10 text-warning">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart Section --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-4">Sales Overview</h5>
                    <canvas id="salesChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script Chart --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    
    // Gradient Color
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(54, 162, 235, 0.5)'); 
    gradient.addColorStop(1, 'rgba(54, 162, 235, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($dates) !!}, 
            datasets: [{
                label: 'Sales ($)',
                data: {!! json_encode($salesData) !!},
                borderColor: '#36a2eb',
                backgroundColor: gradient,
                borderWidth: 2,
                tension: 0.4,
                pointRadius: 4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { borderDash: [2, 2] } },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endsection