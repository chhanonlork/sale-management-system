<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    // ១. បង្ហាញបញ្ជីបុគ្គលិក
    public function index()
    {
        $employees = Employee::orderBy('id', 'desc')->get();
        return view('employees.index', compact('employees'));
    }

    // ២. រក្សាទុកបុគ្គលិកថ្មី
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'position' => 'required',
            'salary' => 'required|numeric',
            'phone' => 'required',
            'start_date' => 'required|date',
        ]);

        Employee::create([
            'name' => $request->name,
            'position' => $request->position,
            'salary' => $request->salary,
            'phone' => $request->phone,
            'email' => $request->email,
            'start_date' => $request->start_date,
        ]);

        return redirect()->route('employees.index')->with('success', 'រក្សាទុកបុគ្គលិកជោគជ័យ');
    }

    // ៣. កែប្រែទិន្នន័យ (UPDATE) - បងខ្វះកន្លែងនេះ
    public function update(Request $request, $id)
    {
        // Validation ដូច store ដែរ
        $request->validate([
            'name' => 'required',
            'position' => 'required',
            'salary' => 'salary',
            'phone' => 'required',
            'start_date' => 'required|date',
        ]);

        // រកបុគ្គលិកតាម ID
        $employee = Employee::findOrFail($id);

        // Update ទិន្នន័យ
        $employee->update([
            'name' => $request->name,
            'position' => $request->position,
            'salary' => $request->salary,
            'phone' => $request->phone,
            'email' => $request->email,
            'start_date' => $request->start_date,
        ]);

        return redirect()->route('employees.index')->with('success', 'កែប្រែទិន្នន័យជោគជ័យ');
    }

    // ៤. លុបបុគ្គលិក
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'លុបជោគជ័យ');
    }
}