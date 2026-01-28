@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0"><i class="fas fa-file-invoice-dollar text-primary me-2"></i>របាយការណ៍លក់</h5>
        </div>
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>កាលបរិច្ឆេទ</th>
                        <th>លេខវិក្កយបត្រ</th>
                        <th>អតិថិជន</th>
                        <th>អ្នកលក់</th>
                        <th class="text-end">សរុបទឹកប្រាក់</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                    <tr>
                        <td>{{ $sale->created_at->format('d/m/Y h:i A') }}</td>
                        <td class="fw-bold text-primary">{{ $sale->invoice_number }}</td>
                        <td>{{ $sale->customer->name ?? 'ភ្ញៀវទូទៅ' }}</td>
                        <td>{{ $sale->user->name }}</td>
                        <td class="text-end fw-bold text-success">${{ number_format($sale->total, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-4 text-muted">មិនមានទិន្នន័យលក់ទេ</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection