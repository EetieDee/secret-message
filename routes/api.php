<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;

Route::post('/messages', [MessageController::class, 'create']);
Route::get('/messages/{id}', [MessageController::class, 'read']);
