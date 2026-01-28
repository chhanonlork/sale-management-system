<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;



class PosController extends Controller
{
    // 1. បង្ហាញមុខហាង POS
    public function index()
    {
        // កែមក select all សិន ដើម្បីកុំឱ្យជាប់ error រឿង status
        // បើអ្នកបាន Run migration "status" ជោគជ័យហើយ ចាំបើកកូដខាងក្រោមវិញ
        // $products = Product::where('status', 'active')->where('qty', '>', 0)->get();

        $products = Product::all();
        $categories = Category::all(); // ១. ទាញទិន្នន័យ Category ទាំងអស់

        // ២. បញ្ជូនទាំង products និង categories ទៅ View
        return view('pos.index', compact('products', 'categories'));
    }

    // 2. មុខងារបន្ថែមចូលកន្ត្រក
    public function addToCart(Request $request)
    {
        return response()->json(['message' => 'Item added - Handled by Frontend JS']);
    }

    // 3. មុខងារគិតលុយ (Checkout) - Updated
    public function checkout(Request $request)
    {
        // ក. ផ្ទៀងផ្ទាត់ទិន្នន័យ (Validation)
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'final_total' => 'required|numeric|min:0',
            'payment_type' => 'required|string|in:Cash,QR,Card',
        ]);

        DB::beginTransaction();

        try {
            // ខ. បង្កើត Invoice
            $invNumber = 'INV-' . date('Ymd') . '-' . time();

            $sale = Sale::create([
                'user_id' => Auth::id(),
                'customer_id' => $request->customer_id,
                'invoice_number' => $invNumber,
                'total_amount' => $request->total_amount,
                'discount' => $request->discount ?? 0,
                'tax' => $request->tax ?? 0,
                'final_total' => $request->final_total,
                'payment_type' => $request->payment_type,
            ]);

            // គ. រក្សាទុកទំនិញលក់ និងកាត់ស្តុក
            foreach ($request->items as $item) {

                // 1. ទាញយកទិន្នន័យផលិតផល (Lock ដើម្បីសុវត្ថិភាព)
                $product = Product::lockForUpdate()->find($item['id']);

                // *** ចំណុចកែប្រែ: ប្រើ 'qty' ជំនួស 'stock_quantity' ***
                // សូមប្រាកដថា column ក្នុង table products របស់អ្នកឈ្មោះ 'qty'
                if ($product->qty < $item['qty']) {
                    throw new \Exception("ទំនិញ '{$product->name}' មិនគ្រាប់គ្រាន់ក្នុងស្តុក! (សល់: {$product->qty})");
                }

                // 2. បង្កើត Sale Detail
                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $item['qty'],
                    'unit_price' => $item['price'],
                    'subtotal' => $item['qty'] * $item['price'],
                ]);

                // 3. កាត់ស្តុកចេញពី Product Table (ប្រើ column 'qty')
                $product->decrement('qty', $item['qty']);

                // 4. (Optional) កត់ត្រាប្រវត្តិស្តុក
                // បើមិនទាន់មាន Table stock_transactions ទេ អាច comment ចោលសិន
                if (class_exists(StockTransaction::class)) {
                    StockTransaction::create([
                        'product_id' => $product->id,
                        'type' => 'sale',
                        'quantity' => -$item['qty'],
                        'reference_id' => $sale->id,
                        'note' => "Sold in Invoice #{$invNumber}",
                        'user_id' => Auth::id(),
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'ការលក់ជោគជ័យ!',
                'sale_id' => $sale->id
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'មានបញ្ហា: ' . $e->getMessage()
            ], 500);
        }
    }

    // 4. បោះពុម្ពវិក្កយបត្រ
    public function printReceipt($id)
    {
        $sale = Sale::with(['user', 'customer', 'saleDetails.product'])->findOrFail($id);
        return view('pos.receipt', compact('sale'));
    }
}