<?php

namespace App\Http\Controllers;

use App\Models\Produto;
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

    /**
     * Produtos Controller
     * @OA\Post(
     *     path="/api/produto/create",
     *     summary="Create Produto",
     *     tags={"Produto"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="price",
     *                     type="double"
     *                 ),
     *                 @OA\Property(
     *                     property="date_expiration",
     *                     type="datetime"
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="id_categoria",
     *                     type="int"
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
            if($request->hasFile('image')){
                // Get filename with the extension
                $filenameWithExt = $request->file('image')->getClientOriginalName();
                // Get just filename
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                // Get just ext
                $extension = $request->file('image')->getClientOriginalExtension();
                // Filename to store
                $fileNameToStore= $filename.'_'.time().'.'.$extension;
                // Upload Image
                $path = $request->file('image')->storeAs('public/img', $fileNameToStore);
            } else {
                $fileNameToStore = 'noimage.png';
            }
            //save in database
            $produto = Produto::create([
                'name'            => $request->name,
                'description'     => mb_strtolower($request->description),
                'price'           => $request->price,
                'date_expiration' => mb_strtolower($request->date_expiration),
                'image'           => $fileNameToStore,
                'id_categoria'    => $request->id_categoria
            ]);

            return response()->json([
                "msg" => 'Produto adicionado com sucesso!',
                "produto" => $produto,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                "erros" => $e->errors(),
            ], 200);
        }
    }
}
