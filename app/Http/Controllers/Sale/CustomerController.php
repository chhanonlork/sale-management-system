<?php

namespace App\Http\Controllers\Sale; // ✅ ត្រូវមាន \Sale ព្រោះវានៅក្នុង Folder Sale

use App\Http\Controllers\Controller; // ✅ ត្រូវហៅ Controller ដើមមកប្រើ
use Illuminate\Http\Request;
use App\Models\Customer;


class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->paginate(10);
        // សូមប្រាកដថា View នេះបានបង្កើតហើយនៅក្នុង resources/views/customers/index.blade.php
        return view('customers.index', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        Customer::create($request->all());

        return redirect()->back()->with('success', 'បង្កើតអតិថិជនជោគជ័យ');
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        $customer->update($request->all());

        return redirect()->back()->with('success', 'កែប្រែទិន្នន័យជោគជ័យ');
    }

    public function destroy($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $customer->delete();
            return redirect()->back()->with('success', 'លុបអតិថិជនជោគជ័យ');

        } catch (\Illuminate\Database\QueryException $e) {
            // លេខកូដ 1451 គឺបញ្ហាជាប់ Foreign Key (មានទិន្នន័យលក់)
            if ($e->errorInfo[1] == 1451) {
                return redirect()->back()->with('error', 'មិនអាចលុបបានទេ! អតិថិជននេះធ្លាប់មានប្រវត្តិទិញទំនិញ (Sales) នៅក្នុងប្រព័ន្ធ។');
            }
            // ករណី Error ផ្សេងៗទៀត
            return redirect()->back()->with('error', 'មានបញ្ហាក្នុងការលុប៖ ' . $e->getMessage());
        }
    }
}