<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'supplier'])->latest()->paginate(10);
        $categories = Category::all();
        $suppliers = Supplier::all();

        return view('products.index', [
            'products' => $products,
            'categories' => $categories,
            'suppliers' => $suppliers
        ]);
    }

    public function store(Request $request)
    {
        // ✅ កែឈ្មោះ Validation ឱ្យត្រូវតាម Database (sale_price)
        $request->validate([
            'name' => 'required|string|max:255',
            'sale_price' => 'required|numeric',
            'category_id' => 'nullable',
            'supplier_id' => 'nullable',
            'cost_price' => 'nullable|numeric',
            'qty' => 'nullable|integer',
            'image' => 'nullable|image|max:2048',
            'barcode' => 'nullable|unique:products,barcode',
        ]);

        // ✅ យកទិន្នន័យតាមឈ្មោះដែលត្រូវនឹង Database
        $data = $request->only([
            'name',
            'barcode',
            'category_id',
            'supplier_id',
            'cost_price',
            'sale_price',
            'qty' // ប្រើ cost_price & sale_price
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'រក្សាទុកទិន្នន័យជោគជ័យ');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'sale_price' => 'required|numeric', // ✅ sale_price
            'qty' => 'nullable|integer',
            'supplier_id' => 'nullable',
        ]);

        // ✅ ប្រើ cost_price & sale_price
        $data = $request->only([
            'name',
            'barcode',
            'category_id',
            'supplier_id',
            'cost_price',
            'sale_price',
            'qty'
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'បានកែប្រែទិន្នន័យជោគជ័យ');
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();

            return redirect()->back()->with('success', 'លុបផលិតផលជោគជ័យ!');

        } catch (QueryException $e) {
            // ២. ចាប់ Error កូដ 23000 (ជាប់ Foreign Key)
            if ($e->getCode() == "23000") {
                return redirect()->back()->with('error', 'មិនអាចលុបបានទេ! ផលិតផលនេះធ្លាប់មានការលក់រួចហើយ។');
            }
            // Error ផ្សេងៗទៀត
            return redirect()->back()->with('error', 'មានបញ្ហាក្នុងការលុប៖ ' . $e->getMessage());
        }
    }
}