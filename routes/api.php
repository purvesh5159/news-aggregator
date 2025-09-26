<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ArticleController;

Route::get('articles', [ArticleController::class, 'index']);
Route::get('articles/{id}', [ArticleController::class, 'show']);
Route::get('sources', [ArticleController::class, 'sources']);
Route::post('preferences', [ArticleController::class, 'savePreferences']);
