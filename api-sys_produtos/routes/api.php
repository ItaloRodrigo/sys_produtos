<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\UserController;
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

Route::get('/', function () {
    return "Seja bem vindo à API Sys_Produtos";
})->name('home');

Route::get('/sem-auth', function () {
    return "É necessário se autenticar para acessar os endpoints dessa API";
})->name('sem-auth');

//----------------------------------------------------------------------------------

Route::prefix('auth')->group(function(){
    Route::post('login', [LoginController::class,'authenticate']);
    Route::post('logout', [LoginController::class,'logout']);
});

Route::prefix("dashboard")->group(function(){
    Route::get("/get",[DashboardController::class,'getDashboardData']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('auth')->group(function(){
        Route::get('isloged/{id}', [LoginController::class,'isLoged']);
    });

    Route::prefix('user')->group(function(){
        Route::get('/get/{id}', [ProdutoController::class, 'get']);
        Route::get('/all', [UserController::class, 'all']);
        Route::post('/create', [UserController::class, 'create']);
        Route::post('/update', [UserController::class, 'update']);
        Route::get('/delete/{id}', [UserController::class, 'delete']);
        Route::get('/pagination/{page}', [UserController::class, 'pagination']);
    });

    Route::prefix('produto')->group(function(){
        Route::get('/get/{id}', [ProdutoController::class, 'get']);
        Route::get('/all', [ProdutoController::class, 'all']);
        Route::post('/create', [ProdutoController::class, 'create']);
        Route::post('/update/{id}', [ProdutoController::class, 'update']);
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

});






