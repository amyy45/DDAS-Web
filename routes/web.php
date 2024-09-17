<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DuplicationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


// Route to display downloaded files
Route::get('/files', [DuplicationController::class, 'index'])->name('files.index');

// Route to download a file from URL
Route::post('/files/download-url', [DuplicationController::class, 'downloadFromUrl'])->name('files.downloadFromUrl');
