<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReceiptController;


Route::get('/', fn () => view('home'))->name('home');
Route::get('/halamanutama', fn () => view('layouts.halamanutama'))->name('halamanutama');
Route::get('/menus', [MenuController::class, 'getMenus']);
Route::get('/order', [OrderController::class, 'index'])->name('order');
Route::post('/receipt', [ReceiptController::class, 'store'])->name('receipt.store');
Route::get('/receipt/{orderId?}', [ReceiptController::class, 'show'])->name('receipt.show');




