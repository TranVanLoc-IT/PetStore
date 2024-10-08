<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers;

Route::any('/', [App\Http\Controllers\DashboardController::class, 'GetDashboard']);
Route::get('/store/expenseChart/{month}', [App\Http\Controllers\DashboardController::class, 'GetStoreExpense']);
Route::get('/pet/revenueChart/{month}', [App\Http\Controllers\DashboardController::class, 'GetPetRevenue']);


Route::get('/hoa-don', [App\Http\Controllers\InvoiceController::class, 'GetInvoice']);
Route::delete('/hoa-don/chi-tiet/{id}', [App\Http\Controllers\InvoiceController::class, 'GetSpecificInvoice']);

Route::prefix('hop-dong')->group(function(){
    Route::get('/chi-tiet/{id}', [App\Http\Controllers\ContractController::class, 'GetDetailContract']);
    Route::get('/du-lieu/{table}', [App\Http\Controllers\ContractController::class, 'GetDataSelectProductList']);
    Route::redirect('/', '/hop-dong/1/5');
    Route::get('/{from?}/{to?}', [App\Http\Controllers\ContractController::class, 'GetContract']);
    Route::post('/edit', [App\Http\Controllers\ContractController::class, 'CreateContract']);
    Route::put('/edit', [App\Http\Controllers\ContractController::class, 'UpdateContract']);
    Route::delete('/edit', [App\Http\Controllers\ContractController::class, 'DeleteContract']);
});

Route::get('/khuyen-mai', [App\Http\Controllers\PromotionController::class, 'GetPromotion']);
Route::get('/khuyen-mai/chi-tiet/{id}', [App\Http\Controllers\PromotionController::class, 'GetSpecificPromotion']);
Route::post('/khuyen-mai', [App\Http\Controllers\PromotionController::class, 'CreatePromotion']);
Route::delete('/khuyen-mai', [App\Http\Controllers\PromotionController::class, 'DeletePromotion']);

Route::prefix('hang-muc')->group(function(){
    Route::get('/', [App\Http\Controllers\PorfolioController::class, 'GetExport']);
    Route::get('/pet-revenue', [App\Http\Controllers\PorfolioController::class, 'GetPetRevenue']);
    Route::get('/store-expense', [App\Http\Controllers\PorfolioController::class, 'GetStoreExpense']);
});

Route::get('/san-pham', [App\Http\Controllers\PorfolioController::class, 'GetProductData']);
Route::get('/nhan-vien', [App\Http\Controllers\PorfolioController::class, 'GetStaffData']);


Route::prefix('/xuat-file')->group(function(){
    Route::get('/', [App\Http\Controllers\ExportController::class, 'GetExport']);

    Route::get('/pet', [App\Http\Controllers\ExportController::class, 'ExportPetRevenue']);
    Route::get('/store', [App\Http\Controllers\ExportController::class, 'ExportStoreExpense']);
});