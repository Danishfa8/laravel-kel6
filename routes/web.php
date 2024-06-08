<?php

use App\Http\Livewire\Items;
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

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->get('/items', function () {
    return view('items');
})->name('items');

Route::get('/jenis_pajak/{jenis_pajak}', [Items::class, 'pajak_pdf']) -> name('jenis_pajak');

Route::get('/pajak_pdf', [Items::class, 'pajak_pdf']);