<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\UserController;
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

Route::prefix('auth')->group(function(){
    Route::post('login', [LoginController::class,'authenticate']);
    Route::post('logout', [LoginController::class,'logout']);
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', function () {
    return "Seja bem vindo à API Sys_Produtos";
});

Route::prefix('user')->group(function(){
    // Route::get('/get/{id}', [ProdutoController::class, 'show']);
    Route::get('/all', [UserController::class, 'all']);
    // Route::post('/create', [UserController::class, 'create']);
    // Route::post('/update', [UserController::class, 'update']);
});

Route::prefix('produto')->group(function(){
    Route::get('/get/{id}', [ProdutoController::class, 'show']);
    Route::get('/all', [ProdutoController::class, 'all']);
    Route::post('/create', [ProdutoController::class, 'create']);
    Route::post('/update', [ProdutoController::class, 'update']);
    Route::get('/delete/{id}', [ProdutoController::class, 'delete']);
    Route::get('/pagination/{page}', [ProdutoController::class, 'pagination']);
});

Route::prefix('categoria')->group(function(){
    Route::get('/get/{id}', [CategoriaController::class, 'get']);
    Route::get('/all', [CategoriaController::class, 'all']);
    Route::post('/create', [CategoriaController::class, 'create']);
    Route::post('/update', [CategoriaController::class, 'update']);
    Route::get('/delete/{id}', [CategoriaController::class, 'delete']);
    Route::get('/pagination/{page}', [CategoriaController::class, 'pagination']);
});

