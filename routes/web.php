<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BangunRuangController;

Route::get('/bangun-ruang', [BangunRuangController::class, 'index'])->name('show');
Route::get('/bangun-ruang/create', [BangunRuangController::class, 'create'])->name('create');
Route::post('/bangun-ruang/store', [BangunRuangController::class, 'store'])->name('store');

Route::get('/bangun-ruang', [BangunRuangController::class, 'index'])->name('show');
Route::delete('/bangun-ruang/{id}', [BangunRuangController::class, 'destroy'])->name('destroy');
