<?php

use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    Route::prefix('list')->name('list.')->group(function () {
        Route::get('create', [ChecklistController::class, 'create'])->name('create');
        Route::put('store', [ChecklistController::class, 'store'])->name('store');
        Route::get('edit/{checklist}', [ChecklistController::class, 'edit'])->name('edit');
        Route::patch('update/{checklist}', [ChecklistController::class, 'update'])->name('update');
        Route::get('delete/{checklist}', [ChecklistController::class, 'delete'])->name('delete');
        Route::delete('destroy/{checklist}', [ChecklistController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('todo')->name('todo.')->group(function () {
        Route::put('store/{todo}', [TodoController::class, 'store'])->name('store');
        Route::get('edit/{todo}', [TodoController::class, 'edit'])->name('edit');
        Route::patch('update/{todo}', [TodoController::class, 'update'])->name('update');
        Route::get('delete/{todo}', [TodoController::class, 'delete'])->name('delete');
        Route::delete('destroy/{todo}', [TodoController::class, 'destroy'])->name('destroy');
    });
});



require __DIR__.'/auth.php';
