<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ShiftController;
use App\Http\Controllers\Api\KitchenController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\TableController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\DashboardController;


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/dashboard/recent', [DashboardController::class, 'recentActivities']);
    
    // ========== PRODUCTS & CATEGORIES ==========
    Route::apiResource('products', ProductController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::get('/categories/{category}/products', [ProductController::class, 'getByCategory']);
    Route::put('/products/{product}/stock', [ProductController::class, 'updateStock']);
    
    // ========== ORDERS ==========
    Route::apiResource('orders', OrderController::class);
    Route::post('/orders/{order}/items', [OrderController::class, 'addItem']);
    Route::delete('/orders/{order}/items/{itemId}', [OrderController::class, 'removeItem']);
    Route::put('/orders/{order}/items/{itemId}', [OrderController::class, 'updateItemQuantity']);
    Route::post('/orders/{order}/apply-promo', [OrderController::class, 'applyPromo']);
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel']);
    Route::get('/orders/{order}/totals', [OrderController::class, 'calculateTotals']);
    
    // ========== PAYMENTS ==========
    Route::post('/orders/{order}/pay', [PaymentController::class, 'processPayment']);
    Route::post('/orders/{order}/split-pay', [PaymentController::class, 'processSplitPayment']);
    Route::get('/orders/{order}/payments', [PaymentController::class, 'getOrderPayments']);
    Route::post('/payments/{payment}/refund', [PaymentController::class, 'refund']);
    
    // ========== SHIFTS ==========
    Route::post('/shifts/open', [ShiftController::class, 'openShift']);
    Route::post('/shifts/{shift}/close', [ShiftController::class, 'closeShift']);
    Route::get('/shifts/current', [ShiftController::class, 'currentShift']);
    Route::post('/shifts/{shift}/cash-movement', [ShiftController::class, 'cashMovement']);
    Route::get('/shifts/{shift}/x-report', [ShiftController::class, 'getXReport']);
    Route::get('/shifts/{shift}/z-report', [ShiftController::class, 'getZReport']);
    Route::apiResource('shifts', ShiftController::class)->only(['index', 'show']);
    
    // ========== KITCHEN ==========
    Route::get('/kitchen/tickets', [KitchenController::class, 'index']);
    Route::get('/kitchen/tickets/{ticket}', [KitchenController::class, 'show']);
    Route::put('/kitchen/tickets/{ticket}', [KitchenController::class, 'updateStatus']);
    Route::put('/kitchen/ticket-items/{item}', [KitchenController::class, 'updateItemStatus']);
    Route::get('/kitchen/status/{status}', [KitchenController::class, 'getOrdersByStatus']);
    
    // ========== CUSTOMERS ==========
    Route::apiResource('customers', CustomerController::class);
    Route::get('/customers/{customer}/points', [CustomerController::class, 'getPoints']);
    
    // ========== TABLES ==========
    Route::apiResource('tables', TableController::class);
    Route::put('/tables/{table}/status', [TableController::class, 'updateStatus']);
    Route::get('/tables/available/all', [TableController::class, 'getAvailable']);
    Route::post('/tables/{table}/qr-code', [TableController::class, 'generateQRCode']);
    
    // ========== REPORTS ==========
    Route::get('/reports/daily', [ReportController::class, 'dailyReport']);
    Route::get('/reports/sales', [ReportController::class, 'salesReport']);
    Route::get('/reports/top-products', [ReportController::class, 'topProducts']);
    Route::get('/reports/payment-breakdown', [ReportController::class, 'paymentBreakdown']);
});