<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;

class ReportController extends Controller
{
    // Redirect ចូលទៅ Sales មុនគេ
    public function index()
    {
        return redirect()->route('reports.sales');
    }

    // 🟢 ១. របាយការណ៍លក់ (បង្ហាញជាបញ្ជីវិក្កយបត្រ #INV-xxxx)
    public function sales(Request $request)
    {
        $query = Sale::query();

        // Filter តាមថ្ងៃ
        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $totalRevenue = $query->sum('total_amount');

        // ទាញទិន្នន័យសម្រាប់តារាងលក់
        $sales = $query->with(['user', 'customer'])->latest()->get();

        return view('reports.index', compact('sales', 'totalRevenue'))->with('activeTab', 'sales');
    }

    // 🟠 ២. ស្តុកបច្ចុប្បន្ន
    public function stocks()
    {
        $products = Product::orderBy('qty', 'asc')->paginate(10);
        return view('reports.index', compact('products'))->with('activeTab', 'stocks');
    }

    // 🔴 ៣. ប្រវត្តិប្រតិបត្តិការ (បង្ហាញការលក់ចេញ ជា Stock Out)
    public function transactions(Request $request)
    {
        $type = $request->type ?? 'all'; // យកប្រភេទពី URL (លំនាំដើមគឺ 'all')
        $transactions = collect(); // បង្កើត Collection ទទេ

        // ======================================================
        // 1. ទាញទិន្នន័យ "Stock Out" (ពីតារាង Sale)
        // ======================================================
        if ($type == 'all' || $type == 'out') {
            $sales = Sale::with('user')->latest()->limit(50)->get();

            $salesData = $sales->map(function ($sale) {
                return (object) [
                    'date' => $sale->created_at,
                    'type' => 'out',
                    'badge_class' => 'warning', // ពណ៌លឿង
                    'status' => 'Stock Out',
                    'item' => 'វិក្កយបត្រ #' . ($sale->invoice_number ?? $sale->id),
                    'user' => $sale->user->name ?? 'N/A',
                    'amount' => $sale->total_amount
                ];
            });
            $transactions = $transactions->merge($salesData);
        }

        // ======================================================
        // 2. ទាញទិន្នន័យ "Stock In" (ប្រសិនបើបងមាន Model StockIn)
        // ======================================================
        if ($type == 'all' || $type == 'in') {
            // ✅ ទាញទិន្នន័យពីតារាង Product ដែលបងមានស្រាប់
            $products = \App\Models\Product::latest()->limit(50)->get();

            $inData = $products->map(function ($product) {
                return (object) [
                    'date' => $product->created_at, 
                    'type' => 'in',
                    'status' => 'Stock In',
                    'item' => $product->name, 
                    'user' => 'Admin', 
                    'amount' => $product->qty, 
                ];
            });
            $transactions = $transactions->merge($inData);
        }

        // ======================================================
        // 3. រៀបចំទិន្នន័យតាមលំដាប់ថ្ងៃ (ថ្មីទៅចាស់)
        // ======================================================
        $transactions = $transactions->sortByDesc('date');

        return view('reports.index', compact('transactions'))->with('activeTab', 'transactions');
    }
}