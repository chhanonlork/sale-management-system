@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">

        {{-- ក្បាលទំព័រ --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-dark mb-0">
                @if($activeTab == 'sales')
                    <i class="fas fa-file-invoice-dollar me-2 text-primary"></i> របាយការណ៍លក់ (Sales Report)
                @elseif($activeTab == 'transactions')
                    <i class="fas fa-history me-2 text-warning"></i> ប្រវត្តិប្រតិបត្តិការ (Transaction History)
                @else
                    <i class="fas fa-boxes me-2 text-info"></i> របាយការណ៍ស្តុក (Stock Report)
                @endif
            </h4>
        </div>

        <div class="card shadow-sm border-0">
            {{-- ផ្នែក Tab Menu --}}
            <div class="card-header bg-white border-bottom">
                <ul class="nav nav-tabs card-header-tabs">
                    {{-- Tab 1: Sales --}}
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab == 'sales' ? 'active fw-bold text-primary' : 'text-muted' }}"
                            href="{{ route('reports.sales') }}">
                            <i class="fas fa-file-invoice-dollar me-2"></i> លក់ (Sales)
                        </a>
                    </li>
                    {{-- Tab 2: Stocks --}}
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab == 'stocks' ? 'active fw-bold text-primary' : 'text-muted' }}"
                            href="{{ route('reports.stocks') }}">
                            <i class="fas fa-boxes me-2"></i> ស្តុក (Stocks)
                        </a>
                    </li>
                    {{-- Tab 3: Transactions --}}
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab == 'transactions' ? 'active fw-bold text-primary' : 'text-muted' }}"
                            href="{{ route('reports.transactions') }}">
                            <i class="fas fa-history me-2"></i> ប្រវត្តិ (Transactions)
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body p-4">

                {{-- ======================================================= --}}
                {{-- 🟢 ផ្នែកទី ១: របាយការណ៍លក់ (បង្ហាញវិក្កយបត្រ) --}}
                {{-- ======================================================= --}}
                @if($activeTab == 'sales')

                    {{-- Form ស្វែងរក --}}
                    <form action="{{ route('reports.sales') }}" method="GET" class="mb-4">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-3">
                                <label class="small fw-bold">ចាប់ពីថ្ងៃ:</label>
                                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="small fw-bold">ដល់ថ្ងៃ:</label>
                                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i>
                                    ស្វែងរក</button>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="bg-success bg-opacity-10 p-2 rounded border border-success d-inline-block px-4">
                                    <small class="text-success fw-bold d-block">ចំណូលសរុប</small>
                                    <h4 class="mb-0 fw-bold text-success">$ {{ number_format($totalRevenue ?? 0, 2) }}</h4>
                                </div>
                            </div>
                        </div>
                    </form>

                    {{-- តារាងលក់ --}}
                    <div class="table-responsive">
                        <table class="table table-hover align-middle border">
                            <thead class="table-light">
                                <tr>
                                    <th>លេខវិក្កយបត្រ</th>
                                    <th>កាលបរិច្ឆេទ</th>
                                    <th>អតិថិជន</th>
                                    <th>អ្នកលក់</th>
                                    <th class="text-center">បង់ប្រាក់</th>
                                    <th class="text-end">តម្លៃសរុប</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sales as $sale)
                                    <tr>
                                        <td class="fw-bold text-primary ps-3">#{{ $sale->invoice_number ?? $sale->id }}</td>
                                        <td class="small text-muted">{{ $sale->created_at->format('d-M-Y h:i A') }}</td>
                                        <td>{{ $sale->customer->name ?? 'General' }}</td>
                                        <td>{{ $sale->user->name ?? 'N/A' }}</td>
                                        <td class="text-center"><span class="badge bg-success">Cash</span></td>
                                        <td class="text-end fw-bold pe-3">$ {{ number_format($sale->total_amount, 2) }}</td>
                                        <td class="text-center">
                                            {{-- ប៊ូតុង Print ពណ៌ខៀវ --}}
                                            <a href="{{ route('pos.print', $sale->id) }}" target="_blank"
                                                class="btn btn-sm btn-primary" title="បោះពុម្ពវិក្កយបត្រ">
                                                <i class="fas fa-print"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">មិនទាន់មានទិន្នន័យលក់</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- ======================================================= --}}
                    {{-- 🟠 ផ្នែកទី ២: ស្តុក (Stocks) --}}
                    {{-- ======================================================= --}}
                @elseif($activeTab == 'stocks')
                    <div class="table-responsive">
                        <table class="table table-hover border">
                            <thead class="table-light">
                                <tr>
                                    <th>ទំនិញ</th>
                                    <th>តម្លៃ</th>
                                    <th class="text-center">ចំនួនស្តុក</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td class="fw-bold">{{ $product->name }}</td>
                                        <td>$ {{ $product->price }}</td>
                                        <td class="text-center fw-bold {{ $product->qty < 10 ? 'text-danger' : 'text-success' }}">
                                            {{ $product->qty }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- ======================================================= --}}
                    {{-- 🔴 ផ្នែកទី ៣: ប្រវត្តិប្រតិបត្តិការ (Transactions) --}}
                    {{-- ======================================================= --}}
                @elseif($activeTab == 'transactions')

                    <div class="d-flex flex-wrap gap-2 mb-4">
                        {{-- ប៊ូតុង All --}}
                        <a href="{{ route('reports.transactions', ['type' => 'all']) }}"
                            class="btn {{ request('type') == 'all' || !request('type') ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill px-4">
                            <i class="fas fa-list me-1"></i> ទាំងអស់
                        </a>

                        {{-- ប៊ូតុង Stock In --}}
                        <a href="{{ route('reports.transactions', ['type' => 'in']) }}"
                            class="btn {{ request('type') == 'in' ? 'btn-success' : 'btn-outline-success' }} rounded-pill px-4">
                            <i class="fas fa-arrow-down me-1"></i> នាំចូល (In)
                        </a>

                        {{-- ប៊ូតុង Stock Out --}}
                        <a href="{{ route('reports.transactions', ['type' => 'out']) }}"
                            class="btn {{ request('type') == 'out' ? 'btn-warning text-dark' : 'btn-outline-warning' }} rounded-pill px-4">
                            <i class="fas fa-arrow-up me-1"></i> នាំចេញ (Out)
                        </a>

                        {{-- ប៊ូតុង Transfer --}}
                        <a href="{{ route('reports.transactions', ['type' => 'transfer']) }}"
                            class="btn {{ request('type') == 'transfer' ? 'btn-info text-white' : 'btn-outline-info' }} rounded-pill px-4">
                            <i class="fas fa-exchange-alt me-1"></i> ផ្ទេរ (Transfer)
                        </a>

                        {{-- ប៊ូតុង Broken --}}
                        <a href="{{ route('reports.transactions', ['type' => 'broken']) }}"
                            class="btn {{ request('type') == 'broken' ? 'btn-danger' : 'btn-outline-danger' }} rounded-pill px-4">
                            <i class="fas fa-heart-broken me-1"></i> ខូច (Broken)
                        </a>
                    </div>

                    {{-- 2. តារាងបង្ហាញទិន្នន័យ --}}
                    <div class="table-responsive">
                        <table class="table table-hover align-middle border">
                            <thead class="bg-light">
                                <tr>
                                    <th>កាលបរិច្ឆេទ</th>
                                    <th>ស្ថានភាព (Status)</th>
                                    <th>បរិយាយ (Description)</th>
                                    <th>អ្នកទទួលខុសត្រូវ</th>
                                    <th class="text-center">ចំនួន/ទឹកប្រាក់</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $trans)
                                    <tr>
                                        <td>{{ $trans->date->format('d-M-Y h:i A') }}</td>
                                        <td>
                                            {{-- Badge ផ្លាស់ប្តូរពណ៌តាមប្រភេទ --}}
                                            @php
                                                $badgeClass = 'secondary';
                                                if ($trans->type == 'in')
                                                    $badgeClass = 'success';
                                                if ($trans->type == 'out')
                                                    $badgeClass = 'warning text-dark';
                                                if ($trans->type == 'transfer')
                                                    $badgeClass = 'info text-white';
                                                if ($trans->type == 'broken')
                                                    $badgeClass = 'danger';
                                            @endphp
                                            <span class="badge bg-{{ $badgeClass }} border bg-opacity-75 px-3 py-2 rounded-pill">
                                                {{ $trans->status }}
                                            </span>
                                        </td>
                                        <td class="fw-bold">{{ $trans->item }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-secondary bg-opacity-10 rounded-circle text-center me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 25px; height: 25px;">
                                                    <i class="fas fa-user text-secondary" style="font-size: 0.7rem;"></i>
                                                </div>
                                                <span>{{ $trans->user }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center fw-bold {{ $trans->type == 'in' ? 'text-success' : 'text-danger' }}">
                                            {{ $trans->type == 'in' ? '+' : '-' }} ${{ number_format($trans->amount, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="fas fa-box-open fa-3x mb-3 opacity-25"></i>
                                            <p>មិនមានទិន្នន័យសម្រាប់ប្រភេទនេះទេ</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                @endif
                {{-- ចប់លក្ខខណ្ឌ --}}

            </div>
        </div>
    </div>
@endsection