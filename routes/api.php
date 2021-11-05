<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('books', [App\Http\Controllers\API\BookController::class, 'index']);
Route::get('books/edit/{id}', [App\Http\Controllers\API\BookController::class, 'show']);
Route::post('books/create', [App\Http\Controllers\API\BookController::class, 'store']);
Route::post('books/delete/{id}', [App\Http\Controllers\API\BookController::class, 'destroy']);

Route::post('books/update/{id}', [App\Http\Controllers\API\BookController::class, 'update']);
Route::post('search', [App\Http\Controllers\API\BookController::class, 'search']);
Route::get('csv-export/{data}', [App\Http\Controllers\API\BookController::class, 'csvExport']);
Route::get('xml-export/{data}', [App\Http\Controllers\API\BookController::class, 'xmlExport']);
