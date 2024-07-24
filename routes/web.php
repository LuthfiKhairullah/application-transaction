<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TransaksiController::class, 'index'])->name('transaksi.index');
Route::get('/form_transaksi', [TransaksiController::class, 'create'])->name('transaksi.create');
Route::get('/form_transaksi/{id}', [TransaksiController::class, 'show'])->name('transaksi.show');
Route::post('/form_transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
Route::post('/form_transaksi/{id}', [TransaksiController::class, 'update'])->name('transaksi.update');
Route::post('/get_barang', [TransaksiController::class, 'get_barang'])->name('transaksi.get_barang');

Route::get('/list_barang', [BarangController::class, 'index'])->name('master_barang.index');
Route::post('/add_barang', [BarangController::class, 'store'])->name('master_barang.store');
Route::put('/update_barang/{id}', [BarangController::class, 'update'])->name('master_barang.update');
Route::delete('/delete_barang/{id}', [BarangController::class, 'destroy'])->name('master_barang.destroy');

Route::get('/list_customer', [CustomerController::class, 'index'])->name('master_customer.index');
Route::post('/add_customer', [CustomerController::class, 'store'])->name('master_customer.store');
Route::put('/update_customer/{id}', [CustomerController::class, 'update'])->name('master_customer.update');
Route::delete('/delete_customer/{id}', [CustomerController::class, 'destroy'])->name('master_customer.destroy');
