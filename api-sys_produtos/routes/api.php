<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProdutoController;
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

Route::get('/', function () {
    return "Olá mundo!";
});

Route::prefix('produto')->group(function(){
    Route::get('/show', [ProdutoController::class, 'show']);
});

Route::prefix('categoria')->group(function(){
    Route::get('/show', [CategoriaController::class, 'show']);
});

