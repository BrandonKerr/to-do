<?php

use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;
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

Route::get("/", function () {
    return view("welcome");
});

Route::middleware(["auth", "verified"])->group(function () {
    Route::get("/dashboard", [DashboardController::class, "dashboard"])->name("dashboard");

    Route::prefix("list")->name("list.")->group(function () {
        Route::get("{user}/complete", [ChecklistController::class, "index"])->name("complete");
        Route::get("{user}/index/{all?}", [ChecklistController::class, "index"])->name("index")->withTrashed();
        Route::get("create", [ChecklistController::class, "create"])->name("create");
        Route::put("store", [ChecklistController::class, "store"])->name("store");
        Route::get("edit/{checklist}", [ChecklistController::class, "edit"])->name("edit");
        Route::patch("update/{checklist}", [ChecklistController::class, "update"])->name("update");
        Route::get("delete/{checklist}", [ChecklistController::class, "delete"])->name("delete");
        Route::delete("destroy/{checklist}", [ChecklistController::class, "destroy"])->name("destroy");
    });

    Route::prefix("todo")->name("todo.")->group(function () {
        Route::put("store/{checklist}", [TodoController::class, "store"])->name("store");
        Route::get("edit/{todo}", [TodoController::class, "edit"])->name("edit");
        Route::patch("update/{todo}", [TodoController::class, "update"])->name("update");
        Route::get("delete/{todo}", [TodoController::class, "delete"])->name("delete");
        Route::delete("destroy/{todo}", [TodoController::class, "destroy"])->name("destroy");
    });

    Route::prefix("user")->name("user.")->group(function () {
        Route::get("index", [UserController::class, "index"])->name("index");
        Route::get("edit/{user}", [UserController::class, "edit"])->name("edit");
        Route::patch("update/{user}", [UserController::class, "update"])->name("update");
        Route::get("delete/{user}", [UserController::class, "delete"])->name("delete");
        Route::delete("destroy/{user}", [UserController::class, "destroy"])->name("destroy");
    });
});

require __DIR__ . "/auth.php";
