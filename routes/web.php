<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionReportController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\CartController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
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
    Route::get('/login', 'index')->name('login'); // Halaman login
    Route::post('/login', 'login')->name('login.submit'); // Proses login (POST)
    Route::post('/logout', 'logout')->name('logout'); // Proses logout (POST)
    Route::post('/reset-password/{id}', 'resetPass')->name('reset.password'); // Reset password
});

// Route yang dilindungi oleh middleware auth
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); // Dashboard hanya bisa diakses setelah login

    // Data Master Routes - Hanya bisa diakses oleh Owner atau Superadmin
    Route::prefix('master')->middleware('role:Owner,Superadmin')->group(function () {
        Route::resource('pegawai', PegawaiController::class)->except(['show']);
        Route::get('/transaction-reports', [TransactionReportController::class, 'index'])->name('transaction-reports.index');
    });

    // Routes untuk produk dan member, dapat diakses oleh Owner, Superadmin, dan Pegawai
    Route::prefix('master')->middleware('role:Owner,Superadmin,Pegawai')->group(function () {
        Route::resource('products', ProductController::class)->except(['show']);
        Route::resource('member', MemberController::class)->except(['show']);
    });

    // Transaction Routes - Dapat diakses oleh semua peran yang terautentikasi
    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/create', [TransactionController::class, 'create'])->name('transactions.create');
        Route::post('/', [TransactionController::class, 'store'])->name('transactions.store');
        Route::get('/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
        Route::get('/{transaction}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
        Route::put('/{transaction}', [TransactionController::class, 'update'])->name('transactions.update');
        Route::get('/{transaction}/print', [TransactionController::class, 'print'])->name('transactions.print');
    });

    // Route untuk filter atau pencarian transaksi
    Route::get('/transactions/search', [TransactionController::class, 'search'])->name('transactions.search');

    // Laporan Transaksi
    Route::get('/reports', [TransactionReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export-pdf', [TransactionReportController::class, 'exportPDF'])->name('reports.exportPDF'); // Route untuk Export PDF
});

// Route untuk detail transaksi - bisa diakses oleh semua pengguna yang terautentikasi
Route::get('transactions/{id}/details', [TransactionController::class, 'details'])->name('transactions.details');

// Route untuk pengecekan member
Route::get('/check-member', [MemberController::class, 'showCheckMemberForm'])->name('check.member.form');
Route::post('/check-member', [MemberController::class, 'checkMember'])->name('check.member');

// Routes untuk pengelolaan password reset
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
Route::get('/password/forgot', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Routes untuk keranjang belanja
Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::post('/add-to-cart/{id}', [CartController::class, 'addToCart'])->name('add.to.cart');
Route::delete('/cart/remove/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
