<?php

namespace App\Http\Controllers\Product; // ✅ ត្រូវតែមាន \Product នៅខាងក្រោយ

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::latest()->paginate(10);
        return view('suppliers.index', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        Supplier::create($request->all());

        return redirect()->back()->with('success', 'បង្កើតអ្នកផ្គត់ផ្គង់ជោគជ័យ');
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $supplier->update($request->all());

        return redirect()->back()->with('success', 'កែប្រែទិន្នន័យជោគជ័យ');
    }

    public function destroy($id)
    {
        Supplier::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'លុបជោគជ័យ');
    }
}