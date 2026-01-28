<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ReportController; // ✅ ត្រូវប្រាកដថាមាន
use App\Http\Controllers\Admin\PosController;    // ✅ ត្រូវប្រាកដថាមាន
use App\Http\Controllers\Product\CategoryController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Product\SupplierController;
use App\Http\Controllers\Employee\PositionController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\Inventory\StockController;
use App\Http\Controllers\Sale\SaleController;
use App\Http\Controllers\Sale\CustomerController;
// use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Setting\SettingController;
use App\Http\Controllers\Auth\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ១. Authentication Routes
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================================
// Protected Routes (តម្រូវឱ្យ Login ជាមុន)
// ==========================================
Route::middleware(['auth'])->group(function () {

    // 2. Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 3. POS System
    Route::prefix('pos')->group(function () {
        Route::get('/', [PosController::class, 'index'])->name('pos.index');
        Route::post('/cart/add', [PosController::class, 'addToCart'])->name('pos.add');
        Route::post('/checkout', [PosController::class, 'checkout'])->name('pos.checkout');
        
        // ✅ កែសម្រួល៖ ដាក់ឈ្មោះថា 'pos.print' ដើម្បីឱ្យដូចគ្នានឹងប៊ូតុងក្នុង View
        // (សូមប្រាកដថា function ក្នុង Controller ឈ្មោះ 'printReceipt' ឬ 'printInvoice')
        Route::get('/print/{id}', [PosController::class, 'printReceipt'])->name('pos.print'); 
    });

    // 4. Sale Management
    Route::resource('sales', SaleController::class)->only(['index', 'show', 'destroy']);

    // 5. Product Management
    Route::prefix('products')->group(function () {
        Route::resource('products', ProductController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('suppliers', SupplierController::class);
    });

    // 6. Inventory & Stock
    Route::prefix('inventory')->group(function () {
        Route::get('/stocks', [StockController::class, 'index'])->name('inventory.stocks');
        Route::get('/transactions', [StockController::class, 'transactions'])->name('inventory.transactions');
        Route::post('/stock-in', [StockController::class, 'stockIn'])->name('inventory.stockIn');
        Route::post('/stock-out', [StockController::class, 'stockOut'])->name('inventory.stockOut');
    });

    // 7. HR Management
    Route::prefix('hr')->group(function () {
        Route::resource('positions', PositionController::class);
        Route::resource('employees', EmployeeController::class);
    });

    // 8. Customers
    Route::resource('customers', CustomerController::class);
    // 9. Reports (✅ ផ្នែករបាយការណ៍)
    Route::prefix('reports')->group(function () {
        // Redirect ទៅ Sales ពេលចូល /reports
        Route::redirect('/', '/reports/sales')->name('reports.index');

        Route::get('/sales', [ReportController::class, 'sales'])->name('reports.sales');
        Route::get('/stocks', [ReportController::class, 'stocks'])->name('reports.stocks');
        Route::get('/transactions', [ReportController::class, 'transactions'])->name('reports.transactions');
    });

    // 10. Settings
    Route::prefix('settings')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/shop', [SettingController::class, 'updateShop'])->name('settings.updateShop');
        Route::post('/password', [SettingController::class, 'updatePassword'])->name('settings.updatePassword');
    });

});