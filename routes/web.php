<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\LeadController;

Route::get('/', function () {
    return redirect()->route('accounts.create');
});

Route::get('/accounts/create', [AccountController::class, 'create'])
    ->name('accounts.create');

Route::post('/accounts', [AccountController::class, 'store'])
    ->name('accounts.store');

Route::get('/leads/create', [LeadController::class, 'create'])
    ->name('leads.create');

Route::post('/leads', [LeadController::class, 'store'])
    ->name('leads.store');
