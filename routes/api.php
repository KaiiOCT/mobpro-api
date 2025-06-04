<?php

use App\Http\Controllers\BangunRuangController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/bangun-ruang', [BangunRuangController::class, 'index'])->name('show');
Route::get('/bangun-ruang/create', [BangunRuangController::class, 'create'])->name('create');
Route::post('/bangun-ruang/store', [BangunRuangController::class, 'store'])->name('store');

Route::delete('/bangun-ruang/{id}', [BangunRuangController::class, 'destroy'])->name('destroy');
