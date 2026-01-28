<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\ShopSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    // ១. បង្ហាញផ្ទាំង Settings (ទាំងពត៌មានហាង និង Profile)
    public function index()
    {
        // យកទិន្នន័យហាង (យកតែមួយ row ដំបូងគេ)
        $shop = ShopSetting::first(); 
        $user = Auth::user();

        return view('settings.index', compact('shop', 'user'));
    }

    // ២. រក្សាទុកពត៌មានហាង (Shop Info)
    public function updateShop(Request $request)
    {
        $request->validate([
            'shop_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'logo' => 'nullable|image|max:2048',
        ]);

        // ទាញយក Setting ចាស់ ឬ បង្កើតថ្មីបើមិនទាន់មាន
        $shop = ShopSetting::firstOrNew(['id' => 1]);
        
        $data = [
            'shop_name' => $request->shop_name,
            'phone' => $request->phone,
            'address' => $request->address,
        ];

        // Upload Logo បើមាន
        if ($request->hasFile('logo')) {
            // លុប Logo ចាស់ចោលសិន
            if ($shop->logo) {
                Storage::disk('public')->delete($shop->logo);
            }
            $data['logo'] = $request->file('logo')->store('shop', 'public');
        }

        // Save (Update or Create)
        $shop->fill($data)->save();

        return back()->with('success', 'Shop settings updated successfully.');
    }

    // ៣. ប្តូរ Password អ្នកប្រើប្រាស់ (Change Password)
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed', // password_confirmation field required in form
        ]);

        $user = Auth::user();

        // ផ្ទៀងផ្ទាត់ Password ចាស់
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password does not match.']);
        }

        // Update Password ថ្មី
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password changed successfully.');
    }
}