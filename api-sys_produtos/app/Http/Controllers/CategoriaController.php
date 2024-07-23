<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Rules\RuleRequired;
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

    /**
     * Categorias Controller
     * @OA\Post(
     *     path="/api/categoria/create",
     *     summary="Create Categoria",
     *     tags={"Categoria"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * ),
     */

    public function create(Request $request){
        try {

            $request->validate([
                'name'             => [new RuleRequired],
            ]);

            $categoria = Categoria::create([
                'name' => $request->name,
            ]);

            return response()->json([
                "msg" => 'Categoria criada com sucesso!',
                "categoria" => $categoria,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                "erros" => $e->errors(),
            ], 200);
        }
    }


    public function update(Request $request){
        try {

            $request->validate([
                'id'     => [new RuleRequired],
                'name'   => [new RuleRequired],
            ]);

            $categoria = Categoria::where('id',$request->id)
                                ->update([
                                    'name' => $request->name,
                                ]);

            return response()->json([
                "msg" => 'Categoria atualizada com sucesso!',
                "categoria" => $categoria,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                "erros" => $e->errors(),
            ], 200);
        }
    }
}
