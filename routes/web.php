<?php

use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\DashboardController;
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
        Route::delete('destroy/{checklist}', [ChecklistController::class, 'destroy'])->name('destroy');
    });
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
});



require __DIR__.'/auth.php';
