<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return view('products.categories.index', compact('categories'));
    }

    // ✅ 1. បង្ហាញទម្រង់កែប្រែ (Edit Form)
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('products.categories.edit', compact('category'));
    }

    // ✅ 2. រក្សាទុកការកែប្រែ (Update Logic)
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->name,
            // ដាក់ field ផ្សេងៗទៀតបើមាន
        ]);

        return redirect()->route('categories.index')->with('success', 'កែប្រែជោគជ័យ!');
    }

    // ✅ 3. លុបទិន្នន័យ (Delete Logic)
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->back()->with('success', 'លុបជោគជ័យ!');
    }
}