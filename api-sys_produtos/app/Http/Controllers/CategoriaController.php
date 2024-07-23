<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoriaController extends Controller
{

    /**
     * Categorias Controller
     * @OA\Get(
     *     path="/api/categoria/show",
     *     summary="Show Categoria",
     *     tags={"Categoria"},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * ),
     */

    public function show(){
        return "show categorias";
    }
}
