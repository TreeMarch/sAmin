<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\CustomerController;


Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('customers.show');
