<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. ចំណូលលក់ថ្ងៃនេះ (ចាប់ពីតារាង Sales យកតែថ្ងៃនេះ)
        $todaySales = Sale::whereDate('created_at', Carbon::today())->sum('final_total');

        // 2. ទំនិញសរុប (ចាប់ពីតារាង Products)
        $totalProducts = Product::count();

        // 3. បុគ្គលិកសរុប (ចាប់ពីតារាង Users)
        $totalEmployees = User::count();

        // 4. ទំនិញជិតអស់ស្តុក (ចាប់ពីតារាង Products ដែលមាន Qty < 10)
        $lowStockItems = Product::where('qty', '<', 10)->count();

        // ទិន្នន័យសម្រាប់ Chart (៧ ថ្ងៃចុងក្រោយ)
        $salesData = [];
        $dates = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dates[] = $date->format('D'); 
            $salesData[] = Sale::whereDate('created_at', $date)->sum('final_total');
        }

        return view('dashboard.index', compact(
            'todaySales', 
            'totalProducts', 
            'totalEmployees', 
            'lowStockItems', 
            'dates', 
            'salesData'
        ));
    }
}