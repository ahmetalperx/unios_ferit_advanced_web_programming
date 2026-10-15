<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

// ---------------------------------------------------------------------------------------------- //

// Language Switching Route (EN, PL, TR)

Route::get('/lang/{locale}', [LanguageController::class, 'switch']) -> name('lang.switch');

// ---------------------------------------------------------------------------------------------- //

// Root Redirect

Route::get('/', function () {

    return redirect() -> route('projects.index');

});

// ---------------------------------------------------------------------------------------------- //

// Guest Authentication Routes

Route::middleware('guest') -> group(function () {

    Route::get('/login', [AuthController::class, 'showLogin']) -> name('login');

    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister']) -> name('register');

    Route::post('/register', [AuthController::class, 'register']);

});

// ---------------------------------------------------------------------------------------------- //

// Authenticated Application Routes

Route::middleware('auth') -> group(function () {

    Route::post('/logout', [AuthController::class, 'logout']) -> name('logout');

    Route::get('/projects', [ProjectController::class, 'index']) -> name('projects.index');

    Route::get('/projects/create', [ProjectController::class, 'create']) -> name('projects.create');

    Route::post('/projects', [ProjectController::class, 'store']) -> name('projects.store');

    Route::get('/projects/{project}/edit', [ProjectController::class, 'edit']) -> name('projects.edit');

    Route::put('/projects/{project}', [ProjectController::class, 'update']) -> name('projects.update');

});

// ---------------------------------------------------------------------------------------------- //
