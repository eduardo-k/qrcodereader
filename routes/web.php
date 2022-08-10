<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DocumentController;

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

Auth::routes();

Route::get('/', [DocumentController::class, 'index'])->name('home');
Route::post('/', [DocumentController::class, 'store'])->name('uploadPDF');

Route::get('/admin', [AdminController::class, 'index'])->name('admin')->middleware('auth');