<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\ArtigoController;

Route::post('/uploads', [UploadController::class, 'store']);
Route::get('/uploads', [UploadController::class, 'index']);
Route::get('/uploads/{upload}', [UploadController::class, 'show']);
Route::get('/artigos/{artigo}', [ArtigoController::class, 'show'])->name('artigos.show');
