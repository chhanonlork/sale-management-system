<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use App\Models\Sale;
use App\Models\SaleDetail;
// use App\Models\Customer; 

class PosController extends Controller
{
    // ✅ ១. Function សម្រាប់បង្ហាញទំព័រលក់ (POS Page)
    public function index()
    {
        // ទាញយកទិន្នន័យផលិតផល និងប្រភេទ ដើម្បីបង្ហាញលើអេក្រង់
        $products = Product::all();
        $categories = Category::all();

        // $customers = Customer::all();

        // បញ្ជូនទិន្នន័យទៅកាន់ View
        return view('.pos.index', compact('products', 'categories'));
    }

    // ✅ ២. Function សម្រាប់គិតលុយ (Checkout)
    public function checkout(Request $request)
    {
        // ពិនិត្យទិន្នន័យ
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        DB::beginTransaction(); // ចាប់ផ្តើមប្រតិបត្តិការ (សុវត្ថិភាពទិន្នន័យ)

        try {
            // បង្កើតវិក្កយបត្រ (Sale Header)
            $sale = new Sale();
            $sale->invoice_number = 'INV-' . time();
            $sale->customer_id = $request->customer_id ?? 1; // ដាក់ 1 សិន បើអត់រើសភ្ញៀវ
            $sale->user_id = auth()->id() ?? 1; // ដាក់ 1 សិន បើមិនទាន់ Login

            // កំណត់តម្លៃ
            $sale->total_amount = $request->total_amount;
            $sale->final_total = $request->total_amount; 
            $sale->received_amount = $request->received_amount ?? $request->total_amount;

            // បន្ថែម payment_type និង change_amount
            $sale->payment_type = $request->payment_type ?? 'Cash';
            $sale->change_amount = ($sale->received_amount - $sale->final_total);

            $sale->save();

            // កាត់ស្តុក និង Save ទំនិញលម្អិត
            foreach ($request->items as $item) {
                // Lock ទិន្នន័យការពារការកាត់ស្តុកជាន់គ្នា
                $product = Product::lockForUpdate()->find($item['id']);

                if ($product->qty < $item['qty']) {
                    throw new \Exception("ទំនិញ " . $product->name . " នៅសល់តែ " . $product->qty);
                }

                // កាត់បន្ថយស្តុក
                $product->qty -= $item['qty'];
                $product->save();

                // Save ចូល SaleDetail
                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['qty'],
                ]);
            }

            DB::commit(); // រក្សាទុកដាច់ខាត
            return response()->json(['status' => 'success', 'message' => 'ការលក់ជោគជ័យ!']);

        } catch (\Exception $e) {
            DB::rollback(); // បរាជ័យ លុបចោលវិញទាំងអស់
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // ✅ ៣. Function សម្រាប់បោះពុម្ពវិក្កយបត្រ (Print Receipt) - បានបន្ថែមថ្មី
    public function printReceipt($id)
    {
        // ទាញយកទិន្នន័យ Sale តាមរយៈ ID
        // បើបងមាន Relation ឈ្មោះ 'details' ឬ 'items' ក្នុង Model Sale អាចដាក់បន្ថែមក្នុង with() បាន
        $sale = Sale::with(['user', 'customer'])->findOrFail($id);

        // បញ្ជូនទៅកាន់ View វិក្កយបត្រ (ដែលបងបានបង្កើតមុននេះ)
        return view('admin.pos.receipt', compact('sale'));
    }
}