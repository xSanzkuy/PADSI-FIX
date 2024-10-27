<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionReportController;

/*
|---------------------------------------------------------------------
| Web Routes
|---------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them
| will be assigned to the "web" middleware group. Make something great!
|
*/

// Home route
Route::get('/', function () {
    return view('home'); // View untuk halaman home
})->name('home');

// Route untuk login dan logout
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'index')->name('login.form'); // Halaman login
    Route::post('/login', 'login')->name('login.submit'); // Proses login (POST)
    Route::post('/logout', 'logout')->name('logout'); // Proses logout (POST)
    Route::post('/reset-password/{id}', 'resetPass')->name('reset.password'); // Reset password
});

// Route untuk permintaan reset password
Route::get('/password/reset', [LoginController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [LoginController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [LoginController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [LoginController::class, 'reset'])->name('password.update');

// Route yang dilindungi oleh middleware auth
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); // Dashboard hanya bisa diakses setelah login

    // Data Master Routes - Batasi akses dengan middleware role:Owner, Superadmin
    Route::prefix('master')->middleware('role:Owner,Superadmin')->group(function () {
        Route::resource('penjualan', PenjualanController::class)->except(['show']); // Route untuk halaman penjualan
        Route::resource('pegawai', PegawaiController::class)->except(['show']); // Route untuk halaman pegawai
        Route::resource('products', ProductController::class)->except(['show']); // Route untuk halaman produk
        Route::resource('member', MemberController::class)->except(['show']); // Route untuk halaman member
        Route::get('/transaction-reports', [TransactionReportController::class, 'index'])->name('transaction-reports.index');
    });

    // Transaction Routes
    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/create', [TransactionController::class, 'create'])->name('transactions.create');
        Route::post('/', [TransactionController::class, 'store'])->name('transactions.store');
        Route::get('/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
        Route::get('/{transaction}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
        Route::put('/{transaction}', [TransactionController::class, 'update'])->name('transactions.update');
        Route::delete('/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
    });

    // Additional route untuk filter atau pencarian transaksi
    Route::get('/transactions/search', [TransactionController::class, 'search'])->name('transactions.search');

    // Laporan Transaksi
    Route::get('/reports', [TransactionReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export-pdf', [TransactionReportController::class, 'exportPDF'])->name('reports.exportPDF'); // Route untuk Export PDF
});

// Detail transaksi - bisa diakses oleh semua pengguna yang terautentikasi
Route::get('transactions/{id}/details', [TransactionController::class, 'details'])->name('transactions.details');


