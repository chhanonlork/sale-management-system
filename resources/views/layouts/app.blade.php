<!DOCTYPE html>
<html lang="km">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ប្រព័ន្ធគ្រប់គ្រងការលក់ - POS System</title>

    <link href="https://fonts.googleapis.com/css2?family=Battambang:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Battambang', sans-serif;
            background-color: #f8f9fa;
        }

        /* Navbar Styling */
        .navbar-brand {
            font-weight: bold;
            color: #11998e !important;
        }

        .nav-link:hover {
            color: #11998e !important;
        }

        /* Sidebar Main Styling */
        .sidebar {
            min-height: 100vh;
            background: #343a40;
            color: white;
            padding-top: 20px;
        }

        .sidebar a {
            color: #ced4da;
            text-decoration: none;
            padding: 12px 20px;
            display: block;
            transition: 0.3s;
            border-left: 4px solid transparent;
        }

        .sidebar a:hover {
            background: #495057;
            color: white;
            border-left-color: #11998e;
        }

        .sidebar a.active {
            background: #11998e;
            color: white;
            border-left-color: #0e857a;
        }

        /* === Styles សម្រាប់ Sub-Menu (Dropdown) === */
        .sub-menu {
            background: rgba(0, 0, 0, 0.2);
        }
        
        .sub-menu a {
            padding-left: 50px !important; /* រុញចូលក្នុង */
            font-size: 0.9rem;
        }

        .sub-menu a.active {
            background: transparent !important;
            color: #38ef7d !important; /* ពណ៌អក្សរពេល Active ក្នុង Submenu */
            border-left-color: transparent !important;
            font-weight: bold;
        }

        /* ធ្វើឱ្យព្រួញបង្វិលពេលចុច */
        .arrow-icon {
            transition: transform 0.3s ease;
        }
        
        a[aria-expanded="true"] .arrow-icon {
            transform: rotate(90deg);
        }

        /* Digital Theme Utilities */
        :root {
            --digital-primary: #007bff;
            --digital-success: #28a745;
            --digital-bg: #f8f9fa;
            --digital-card-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .table-digital {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--digital-card-shadow);
        }
        .table-digital thead th {
            background-color: #f1f4f8;
            color: #5d6778;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            padding: 15px;
            border: none;
        }
        .text-digital-success {
            color: var(--digital-success) !important;
            font-weight: 700;
            font-size: 1.05rem;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-white bg-white shadow-sm sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="fas fa-store me-2"></i> POS ADMIN
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name ?? 'អ្នកប្រើប្រាស់' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-1"></i> ចាកចេញ
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 d-none d-md-block sidebar px-0">
                
                {{-- Dashboard --}}
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt me-2"></i> ផ្ទាំងគ្រប់គ្រង
                </a>
                
                {{-- POS --}}
                <a href="{{ route('pos.index') }}" class="{{ request()->routeIs('pos.*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart me-2"></i> ប្រព័ន្ធលក់ (POS)
                </a>
                
                {{-- Products --}}
                <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">
                    <i class="fas fa-box me-2"></i> បញ្ជីទំនិញ
                </a>
                
                {{-- Categories --}}
                <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <i class="fas fa-tags me-2"></i> ប្រភេទទំនិញ
                </a>

                <hr class="text-secondary mx-3">

                {{-- =========================================== --}}
                {{-- 1. បុគ្គលិក & ដៃគូ (Dropdown) --}}
                {{-- =========================================== --}}
                <a href="#hrSubMenu" 
                   data-bs-toggle="collapse" 
                   class="{{ request()->routeIs('employees.*') || request()->routeIs('suppliers.*') ? '' : 'collapsed' }}"
                   aria-expanded="{{ request()->routeIs('employees.*') || request()->routeIs('suppliers.*') ? 'true' : 'false' }}">
                    
                    <i class="fas fa-users me-2"></i> បុគ្គលិក & ដៃគូ
                    <i class="fas fa-chevron-right float-end mt-1 arrow-icon" style="font-size: 0.8rem;"></i>
                </a>

                <div class="collapse {{ request()->routeIs('employees.*') || request()->routeIs('suppliers.*') ? 'show' : '' }}" 
                     id="hrSubMenu">
                    <div class="sub-menu">
                        {{-- 1.1 បុគ្គលិក --}}
                        <a href="{{ route('employees.index') }}" 
                           class="{{ request()->routeIs('employees.*') ? 'active' : '' }}">
                            <i class="fas fa-user-tie me-2"></i> បញ្ជីបុគ្គលិក
                        </a>

                        {{-- 1.2 អ្នកផ្គត់ផ្គង់ --}}
                        <a href="{{ route('suppliers.index') }}" 
                           class="{{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                            <i class="fas fa-truck me-2"></i> អ្នកផ្គត់ផ្គង់
                        </a>
                    </div>
                </div>

                {{-- =========================================== --}}
                {{-- 2. សមាជិក (Customers) --}}
                {{-- =========================================== --}}
                <a href="{{ route('customers.index') }}" class="{{ request()->routeIs('customers.*') ? 'active' : '' }}">
                    <i class="fas fa-user-friends me-2 w-20"></i> សមាជិក (Member)
                </a>

                {{-- =========================================== --}}
                {{-- 3. របាយការណ៍ (Dropdown) - កែប្រែថ្មី --}}
                {{-- =========================================== --}}
                <a href="#reportsSubMenu" 
                   data-bs-toggle="collapse" 
                   class="{{ request()->routeIs('reports.*') ? '' : 'collapsed' }}"
                   aria-expanded="{{ request()->routeIs('reports.*') ? 'true' : 'false' }}">
                    
                    <i class="fas fa-chart-line me-2"></i> របាយការណ៍
                    <i class="fas fa-chevron-right float-end mt-1 arrow-icon" style="font-size: 0.8rem;"></i>
                </a>

                <div class="collapse {{ request()->routeIs('reports.*') ? 'show' : '' }}" 
                     id="reportsSubMenu">
                    <div class="sub-menu">
                        {{-- 3.1 របាយការណ៍លក់ --}}
                        <a href="{{ route('reports.sales') }}" 
                           class="{{ request()->routeIs('reports.sales') ? 'active' : '' }}">
                            <i class="fas fa-file-invoice-dollar me-2"></i> របាយការណ៍លក់
                        </a>

                        {{-- 3.2 ស្តុកបច្ចុប្បន្ន --}}
                        <a href="{{ route('reports.stocks') }}" 
                           class="{{ request()->routeIs('reports.stocks') ? 'active' : '' }}">
                            <i class="fas fa-boxes me-2"></i> ស្តុកបច្ចុប្បន្ន
                        </a>

                        {{-- 3.3 ប្រវត្តិប្រតិបត្តិការ --}}
                        <a href="{{ route('reports.transactions') }}" 
                           class="{{ request()->routeIs('reports.transactions') ? 'active' : '' }}">
                            <i class="fas fa-exchange-alt me-2"></i> ប្រវត្តិប្រតិបត្តិការ
                        </a>
                    </div>
                </div>

                {{-- =========================================== --}}
                {{-- 4. ការកំណត់ --}}
                {{-- =========================================== --}}
                <a href="{{ route('settings.index') }}" class="{{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <i class="fas fa-cog me-2"></i> ការកំណត់
                </a>
            </div>

            <div class="col-md-10 p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @yield('scripts')
</body>

</html>