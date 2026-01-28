<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Employee;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // រាប់ចំនួនសរុបសម្រាប់បង្ហាញលើ Card
        $totalSales = Sale::whereDate('created_at', Carbon::today())->sum('final_total');
        $totalProducts = Product::count();
        $totalEmployees = Employee::count();
        $lowStockProducts = Product::where('qty', '<', 10)->count();

        return view('dashboard.index', compact('totalSales', 'totalProducts', 'totalEmployees', 'lowStockProducts'));
    }
}