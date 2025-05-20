<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Dosen;
use App\Http\Controllers\Staf;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dosen', [Dosen::class, 'index'])->name('dosen');
Route::get('/dosen/db', [Dosen::class, 'db_absen_dosen'])->name('dosen_DB');
Route::post('/dosen/absen_dosen', [Dosen::class, 'absen_dosen'])->name('dosen_gas');
Route::post('/dosen/gass', [Dosen::class, 'gas'])->name('gas');
Route::get('/dosen/data', [Dosen::class, 'data']);

Route::get('/dosen/proses', [Dosen::class, 'proses']);
Route::get('/dosen/proses1', [Dosen::class, 'proses1']);
Route::get('/dosen/proses2', [Dosen::class, 'proses2']);
Route::get('/staf', [Staf::class, 'index']);