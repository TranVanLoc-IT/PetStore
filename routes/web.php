<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers;

Route::any('/', [App\Http\Controllers\DashboardController::class, 'GetDashboard']);
Route::get('/store/expenseChart/{month}', [App\Http\Controllers\DashboardController::class, 'GetStoreExpense']);
Route::get('/pet/revenueChart/{month}', [App\Http\Controllers\DashboardController::class, 'GetPetRevenue']);

Route::prefix('/hoa-don')->group(function(){
    Route::get('/{month?}', [App\Http\Controllers\InvoiceController::class, 'GetInvoice']);
    Route::get('/chi-tiet/{id}', [App\Http\Controllers\InvoiceController::class, 'GetDetailInvoice']);
    Route::delete('/xoa-het}', [App\Http\Controllers\InvoiceController::class, 'DeletAll']);
});


Route::prefix('/hop-dong')->group(function(){
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

Route::get('/hang-muc', function(){return view("productView");});

Route::get('/san-pham', [App\Http\Controllers\PorfolioController::class, 'GetProductData']);
Route::get('/nhan-vien', [App\Http\Controllers\PorfolioController::class, 'GetStaffData']);


Route::prefix('/xuat-file')->group(function(){
    Route::get('/', [App\Http\Controllers\ExportController::class, 'GetExport']);
    Route::get('/pet', [App\Http\Controllers\ExportController::class, 'ExportPetRevenue']);
    Route::get('/store', [App\Http\Controllers\ExportController::class, 'ExportStoreExpense']);
});