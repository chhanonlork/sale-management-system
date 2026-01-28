<?php
namespace App\Http\Controllers\Inventory;
use App\Http\Controllers\Controller;
use App\Models\Product;

class StockController extends Controller
{
    public function index()
    {
        // បង្ហាញទំនិញទាំងអស់ និងចំនួន qty ដែលមាន
        $stocks = Product::select('id', 'name', 'qty', 'cost_price', 'sale_price')
                 ->paginate(20);
        return view('inventory.stocks', compact('stocks'));
    }
}