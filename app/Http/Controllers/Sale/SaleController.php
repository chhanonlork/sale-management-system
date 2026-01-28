<?php
namespace App\Http\Controllers\Sale;
use App\Http\Controllers\Controller;
use App\Models\Sale;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with(['user', 'customer'])->latest()->paginate(15);
        return view('sales.index', compact('sales'));
    }

    public function show(Sale $sale)
    {
        // បង្ហាញលម្អិតវិក្កយបត្រ (Invoice Detail)
        $sale->load('saleDetails.product');
        return view('sales.show', compact('sale'));
    }
    
    public function destroy(Sale $sale)
    {
        // ពេលលុប Sale អាចនឹងត្រូវ Restock វិញ (Logic នេះអាស្រ័យលើតម្រូវការ)
        $sale->delete();
        return back()->with('success', 'Sale record deleted.');
    }
}