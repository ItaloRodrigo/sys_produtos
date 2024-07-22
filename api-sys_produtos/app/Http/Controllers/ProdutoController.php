<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

class ProdutoController extends Controller
{


    #[OA\Get(
        path: "/api/produto/show",
        summary: "Mostra produtos",
        security: [
            [
                'bearerAuth' => []
            ]
        ],
        tags: ["Produto"],
        responses: [
            new OA\Response(response: Response::HTTP_OK, description: "users retrieved success")
        ]
    )]

    public function show(){
        return "Olá mundo!";
    }
}
