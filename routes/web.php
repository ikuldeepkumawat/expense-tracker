<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExpenseController;
use Illuminate\Support\Facades\Auth;

// 1. Login/Register Routes
Auth::routes();

// 2. PROTECTED ROUTES (Jo sirf Login wale dekh sakte hain)
// Humne 'middleware' group laga diya hai
Route::middleware(['auth'])->group(function () {
    
    // Home Page
    Route::get('/', [ExpenseController::class, 'index'])->name('home');

    // Add Expense
    Route::get('/add', [ExpenseController::class, 'create']);
    Route::post('/save-expense', [ExpenseController::class, 'store']);
// Baki routes ke saath isse bhi jodein
    Route::get('/profile', [ExpenseController::class, 'profile'])->name('profile');
    // Edit Expense
    Route::get('/edit/{id}', [ExpenseController::class, 'edit']);
    Route::put('/update/{id}', [ExpenseController::class, 'update']);

    // Delete Expense
    Route::delete('/delete/{id}', [ExpenseController::class, 'destroy']);
    // Profile Update Route
    Route::post('/profile/update', [ExpenseController::class, 'updateProfile']);

    // Export Data Route
    Route::get('/export', [ExpenseController::class, 'export'])->name('export');

});