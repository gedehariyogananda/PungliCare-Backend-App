<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PemantauanController;
use App\Models\Laporan;
use Illuminate\Support\Facades\Route;
use Laravel\Jetstream\Rules\Role;

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
    return redirect()->route('login_page');
});

Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/login', [LoginController::class, 'index'])->name('login_page');
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('dashboard', 'index')->name('dashboard');
    });
    Route::controller(PemantauanController::class)->group(function () {
        Route::get('pemantauan', 'index')->name('pemantauan');
    });

    Route::controller(LaporanController::class)->group(function () {
        Route::get('laporan', 'index')->name('laporan');
        Route::get('laporan/{id}','detail')->name('laporan.detail');
        Route::put('laporan/{id}','update')->name('laporan.update');
        Route::delete('laporan/{id}','delete')->name('laporan.delete');
        Route::get('comments/{id}','comment')->name('laporan.comment');
        Route::post('comments/{id}', 'createComment')->name('comment.create');
    });
});


  