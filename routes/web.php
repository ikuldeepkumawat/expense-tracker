<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExpenseController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

// Saare routes login ke baad hi khulenge
Route::middleware(['auth'])->group(function () {
    
    // 1. Dashboard (Home)
    Route::get('/', [ExpenseController::class, 'index'])->name('home');

    // 2. Add New Expense
    Route::get('/add', [ExpenseController::class, 'create']);
    Route::post('/add', [ExpenseController::class, 'store']);

    // 3. Edit & Delete
    Route::get('/edit/{id}', [ExpenseController::class, 'edit']);
    Route::post('/edit/{id}', [ExpenseController::class, 'update']);
    Route::delete('/delete/{id}', [ExpenseController::class, 'destroy']);

    // 4. User Profile
    Route::get('/profile', [ExpenseController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [ExpenseController::class, 'updateProfile']);

    // 5. Export Excel/CSV
    Route::get('/export', [ExpenseController::class, 'export'])->name('export');
});