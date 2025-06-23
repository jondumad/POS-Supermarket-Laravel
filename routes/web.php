<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ReportController;

// User Routes
Route::get('users', [UserController::class, 'index'])->name('users');
Route::get('users/form', [UserController::class, 'create'])->name('users.create');
Route::get('login', function () {
  return view('theme.login');
})->name('login');
Route::post('login', [UserController::class, 'login'])->name('login');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');
Route::post('users', [UserController::class, 'store'])->name('users.store');
// User CRUD
Route::resource('users', UserController::class)->names([
  'index' => 'users',
  'show' => 'users.show',
  'edit' => 'users.edit',
  'create' => 'users.create',
  'store' => 'users.store',
  'update' => 'users.update',
  'destroy' => 'users.destroy',
]);
// Product routes
Route::resource('products', ProductController::class)->names([
  'index' => 'products',
  'show' => 'products.show',
  'edit' => 'products.edit',
  'create' => 'products.create',
  'store' => 'products.store',
  'update' => 'products.update',
  'destroy' => 'products.destroy',
]);
// Orders routes
Route::resource('purchase-orders', PurchaseOrderController::class)->names([
  'create' => 'purchase_orders.create',
  'index' => 'purchase_orders.index',
  'show' => 'purchase_orders.show',
  'edit' => 'purchase_orders.edit',
  'store' => 'purchase_orders.store',
  'update' => 'purchase_orders.update',
  'destroy' => 'purchase_orders.destroy',
]);
Route::post('purchase-orders/{purchaseOrder}/receive', [PurchaseOrderController::class, 'receive'])->name('purchase_orders.receive');
Route::post('purchase-orders/{purchaseOrder}/cancel', [PurchaseOrderController::class, 'cancel'])->name('purchase_orders.cancel');

// Categories routes
Route::resource('categories', CategoryController::class)->names([
  'index' => 'categories.index',
  'show' => 'categories.show',
  'edit' => 'categories.edit',
  'create' => 'categories.create',
  'store' => 'categories.store',
  'update' => 'categories.update',
  'destroy' => 'categories.destroy',
]);

// Customers routes
Route::resource('customers', CustomerController::class)->names([
  'index' => 'customers.index',
  'show' => 'customers.show',
  'edit' => 'customers.edit',
  'create' => 'customers.create',
  'store' => 'customers.store',
  'update' => 'customers.update',
  'destroy' => 'customers.destroy',
]);

// Suppliers routes
Route::resource('suppliers', SupplierController::class)->names([
  'index' => 'suppliers.index',
  'show' => 'suppliers.show',
  'edit' => 'suppliers.edit',
  'create' => 'suppliers.create',
  'store' => 'suppliers.store',
  'update' => 'suppliers.update',
  'destroy' => 'suppliers.destroy',
]);

//Sales Routes
Route::get('sales', [SaleController::class, 'index'])->name('sales.index');
Route::get('sales/create', [SaleController::class, 'create'])->name('sales.create');
Route::post('sales', [SaleController::class, 'store'])->name('sales.store');
Route::get('sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
Route::get('sales/{sale}/edit', [SaleController::class, 'edit'])->name('sales.edit');
Route::put('sales/{sale}', [SaleController::class, 'update'])->name('sales.update');
Route::delete('sales/{sale}', [SaleController::class, 'destroy'])->name('sales.destroy');
Route::post('sales/{sale}/void', [SaleController::class, 'void'])->name('sales.void');

Route::prefix('reports')->name('reports.')->group(function () {
  Route::get('/', [ReportController::class, 'index'])->name('index');
  Route::get('/sales', [ReportController::class, 'salesReport'])->name('sales');
  Route::get('/inventory', [ReportController::class, 'inventoryReport'])->name('inventory');
  Route::get('/customers', [ReportController::class, 'customerReport'])->name('customers');
  Route::get('/financial', [ReportController::class, 'financialReport'])->name('financial');
  Route::get('/product-performance', [ReportController::class, 'productPerformance'])->name('product-performance');

  // Export routes
  Route::get('/export/sales', [ReportController::class, 'exportSalesReport'])->name('export.sales');
  Route::get('/export/inventory', [ReportController::class, 'exportInventoryReport'])->name('export.inventory');
});

// Authentication routes
Route::get('register', function () {
  return view('auth.register');
})->middleware('guest')->name('register');

Route::post('register', [UserController::class, 'store'])->middleware('guest')->name('register.store');
