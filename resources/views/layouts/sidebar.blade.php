<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">

        {{-- Logo / Brand --}}
        <a class="sidebar-brand" href="{{ route('dashboard') }}">
            <span class="align-middle fs-4">POS System</span>
        </a>

        <ul class="sidebar-nav">

            <li class="sidebar-header">
                ផ្ទាំងគ្រប់គ្រង
            </li>

            {{-- 1. Dashboard --}}
            <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('dashboard') }}">
                    <i class="align-middle" data-feather="sliders"></i>
                    <span class="align-middle">ផ្ទាំងគ្រប់គ្រង (Dashboard)</span>
                </a>
            </li>

            {{-- 2. POS (Point of Sale) --}}
            <li class="sidebar-item {{ request()->routeIs('pos.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('pos.index') }}">
                    <i class="align-middle" data-feather="shopping-cart"></i>
                    <span class="align-middle">ប្រព័ន្ធលក់ (POS)</span>
                </a>
            </li>

            <li class="sidebar-header">
                ការគ្រប់គ្រងទិន្នន័យ
            </li>

            {{-- 3. ផលិតផល (Products) - Dropdown --}}
            <li class="sidebar-item {{ request()->routeIs('products.*', 'categories.*', 'suppliers.*') ? 'active' : '' }}">
                <a class="sidebar-link {{ request()->routeIs('products.*', 'categories.*', 'suppliers.*') ? '' : 'collapsed' }}"
                    data-bs-target="#productsMenu" data-bs-toggle="collapse">
                    <i class="align-middle" data-feather="package"></i>
                    <span class="align-middle">ផលិតផល & ទំនិញ</span>
                </a>
                <ul id="productsMenu"
                    class="sidebar-dropdown list-unstyled collapse {{ request()->routeIs('products.*', 'categories.*', 'suppliers.*') ? 'show' : '' }}"
                    data-bs-parent="#sidebar">
                    
                    <li class="sidebar-item {{ request()->routeIs('products.index') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('products.index') }}">
                            <i class="align-middle me-2" data-feather="list"></i> បញ្ជីទំនិញ
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('categories.index') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('categories.index') }}">
                            <i class="align-middle me-2" data-feather="grid"></i> ប្រភេទទំនិញ
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('suppliers.index') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('suppliers.index') }}">
                            <i class="align-middle me-2" data-feather="truck"></i> អ្នកផ្គត់ផ្គង់
                        </a>
                    </li>
                </ul>
            </li>

            {{-- 4. ស្តុក & ឃ្លាំង (Inventory) - Dropdown --}}
            <li class="sidebar-item {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
                <a class="sidebar-link {{ request()->routeIs('inventory.*') ? '' : 'collapsed' }}"
                    data-bs-target="#inventoryMenu" data-bs-toggle="collapse">
                    <i class="align-middle" data-feather="box"></i>
                    <span class="align-middle">ស្តុក & ឃ្លាំង</span>
                </a>
                <ul id="inventoryMenu"
                    class="sidebar-dropdown list-unstyled collapse {{ request()->routeIs('inventory.*') ? 'show' : '' }}"
                    data-bs-parent="#sidebar">
                    
                    <li class="sidebar-item {{ request()->routeIs('inventory.stocks') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('inventory.stocks') }}">
                            <i class="align-middle me-2" data-feather="layers"></i> បញ្ជីស្តុក
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('inventory.transactions') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('inventory.transactions') }}">
                            <i class="align-middle me-2" data-feather="refresh-cw"></i> ប្រតិបត្តិការស្តុក
                        </a>
                    </li>
                </ul>
            </li>

            {{-- 5. អតិថិជន (Customers) --}}
            <li class="sidebar-item {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('customers.index') }}">
                    <i class="align-middle" data-feather="users"></i>
                    <span class="align-middle">អតិថិជន</span>
                </a>
            </li>

            {{-- 6. បុគ្គលិក (HR) - Dropdown --}}
            <li class="sidebar-item {{ request()->routeIs('employees.*', 'positions.*') ? 'active' : '' }}">
                <a class="sidebar-link {{ request()->routeIs('employees.*', 'positions.*') ? '' : 'collapsed' }}"
                    data-bs-target="#hrMenu" data-bs-toggle="collapse">
                    <i class="align-middle" data-feather="user-check"></i>
                    <span class="align-middle">បុគ្គលិក & តួនាទី</span>
                </a>
                <ul id="hrMenu"
                    class="sidebar-dropdown list-unstyled collapse {{ request()->routeIs('employees.*', 'positions.*') ? 'show' : '' }}"
                    data-bs-parent="#sidebar">
                    
                    <li class="sidebar-item {{ request()->routeIs('employees.index') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('employees.index') }}">
                            <i class="align-middle me-2" data-feather="users"></i> បញ្ជីបុគ្គលិក
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('positions.index') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('positions.index') }}">
                            <i class="align-middle me-2" data-feather="award"></i> តួនាទី & ប្រាក់ខែ
                        </a>
                    </li>
                </ul>
            </li>

            <li class="sidebar-header">
                របាយការណ៍ & ការកំណត់
            </li>

            {{-- 7. របាយការណ៍ (Reports) --}}
            <li class="sidebar-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <a class="sidebar-link {{ request()->routeIs('reports.*') ? '' : 'collapsed' }}"
                    data-bs-target="#reportsMenu" data-bs-toggle="collapse">
                    <i class="align-middle" data-feather="pie-chart"></i>
                    <span class="align-middle">របាយការណ៍</span>
                </a>
                <ul id="reportsMenu"
                    class="sidebar-dropdown list-unstyled collapse {{ request()->routeIs('reports.*') ? 'show' : '' }}"
                    data-bs-parent="#sidebar">

                    <li class="sidebar-item {{ request()->routeIs('reports.sales') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('reports.sales') }}">
                            <i class="align-middle me-2" data-feather="dollar-sign"></i> របាយការណ៍លក់
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->routeIs('reports.stocks') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('reports.stocks') }}">
                            <i class="align-middle me-2" data-feather="package"></i> ស្តុកបច្ចុប្បន្ន
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->routeIs('reports.transactions') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('reports.transactions') }}">
                            <i class="align-middle me-2" data-feather="activity"></i> ប្រវត្តិប្រតិបត្តិការ
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Logout --}}
            <li class="sidebar-item mt-5">
                <a class="sidebar-link text-danger" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="align-middle" data-feather="log-out"></i>
                    <span class="align-middle">ចាកចេញ (Logout)</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>

        </ul>
    </div>
</nav>