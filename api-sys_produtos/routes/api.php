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
    Route::get('/get/{id}', [ProdutoController::class, 'show']);
    Route::get('/all', [ProdutoController::class, 'all']);
    Route::post('/create', [ProdutoController::class, 'create']);
    Route::post('/update', [ProdutoController::class, 'update']);
});

Route::prefix('categoria')->group(function(){
    Route::get('/get/{id}', [CategoriaController::class, 'get']);
    Route::get('/all', [CategoriaController::class, 'all']);
    Route::post('/create', [CategoriaController::class, 'create']);
    Route::post('/update', [CategoriaController::class, 'update']);
    Route::get('/delete/{id}', [CategoriaController::class, 'delete']);
    Route::get('/pagination/{page}', [CategoriaController::class, 'pagination']);
});

