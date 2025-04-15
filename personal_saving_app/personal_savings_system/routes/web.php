<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\SavingsGoalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisteredUserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/transactions', [DashboardController::class, 'storeTransaction'])->name('transactions.store');
    Route::post('/savings/contribution', [DashboardController::class, 'storeSaving'])->name('savings.store-contribution');
    
    // Transactions
    Route::resource('transactions', TransactionController::class);
    
    // Savings Goals
    Route::resource('savings', SavingsGoalController::class);
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
});

// Admin Auth Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [App\Http\Controllers\Admin\AdminAuthController::class, 'showLoginForm'])
        ->name('login')
        ->middleware('guest');
    Route::post('login', [App\Http\Controllers\Admin\AdminAuthController::class, 'login'])
        ->middleware('guest');
    Route::post('logout', [App\Http\Controllers\Admin\AdminAuthController::class, 'logout'])
        ->name('logout');
});

// Admin Protected Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [App\Http\Controllers\Admin\AdminController::class, 'users'])->name('users');
    Route::get('/users/{user}', [App\Http\Controllers\Admin\AdminController::class, 'userDetails'])->name('users.show');
    Route::get('/transactions', [App\Http\Controllers\Admin\AdminController::class, 'transactions'])->name('transactions');
    Route::get('/savings-goals', [App\Http\Controllers\Admin\AdminController::class, 'savingsGoals'])->name('savings-goals');
});

require __DIR__.'/auth.php';
