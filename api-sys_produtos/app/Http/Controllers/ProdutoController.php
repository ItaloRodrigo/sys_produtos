<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProdutoController extends Controller
{

    /**
     * Produtos Controller
     * @OA\Get(
     *     path="/api/produto/show",
     *     summary="Show Produto",
     *     tags={"Produto"},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * ),
     */

    public function show(){
        return "Olá mundo!";
    }
}
