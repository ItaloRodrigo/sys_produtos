<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Produto;
use App\Rules\RuleRequired;
use App\Rules\RuleStringMax;
use App\Rules\RuleValidateDate;
use App\Rules\RuleValuePositive;
use App\Utils\Response;
use Illuminate\Http\Request;
use File;
use Illuminate\Support\Facades\Storage;

class ProdutoController extends Controller
{

    /**
     * Produtos Controller
     * @OA\Get(
     *     path="/api/produto/get",
     *     summary="Get Produto",
     *     tags={"Produto"},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * ),
     */

    public function get($id){
        try {
            $produto = Produto::find($id);
            //---
            return Response::successWithData("ok", [
                'produto' => $produto
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return Response::error($e->errors());
        }
    }

    /**
     * Produtos Controller
     * @OA\Get(
     *     path="/api/produto/all",
     *     summary="All Produto",
     *     tags={"Produto"},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * ),
     */

     public function all(){
        try {
            return Response::successWithData("ok", [
                "produtos" => Produto::all()
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return Response::error($e->errors());
        }
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

            $request->validate([
                'name'            => [new RuleRequired, new RuleStringMax(50)],
                'description'     => [new RuleRequired, new RuleStringMax(200)],
                'price'           => [new RuleRequired, new RuleValuePositive],
                'date_expiration' => [new RuleRequired, new RuleValidateDate],
                'image'           => [new RuleRequired],
                'id_categoria'    => [new RuleRequired],
            ]);
            //---
            $existe = Categoria::find($request->id_categoria);
            if($existe){
                if($request->hasFile('image')){
                    // pega o filename com a extensão
                    $filenameWithExt = $request->file('image')->getClientOriginalName();
                    // pega o filename sem extensão
                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    // pega apenas a extensão
                    $extension = $request->file('image')->getClientOriginalExtension();
                    // Salva o filename com o time
                    $fileNameToStore= $filename.'_'.time().'.'.$extension;
                    // Sobe a imagem no servidor
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
                //---
                return Response::successWithData("Produto adicionado com sucesso!", [
                    "produto" => $produto
                ]);
            }else{
                return Response::error([
                    'categoria' => 'essa categoria não existe'
                ]);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return Response::error($e->errors());
        }
    }

    public function update(Request $request){
        try {

            $request->validate([
                'id'              => [new RuleRequired],
                'name'            => [new RuleRequired, new RuleStringMax(50)],
                'description'     => [new RuleRequired, new RuleStringMax(200)],
                'price'           => [new RuleRequired, new RuleValuePositive],
                'date_expiration' => [new RuleRequired, new RuleValidateDate],
                'image'           => [new RuleRequired],
                'id_categoria'    => [new RuleRequired],
            ]);
            //---
            $produto = Produto::find($request->id);
            if($produto){
                $categoria = Categoria::find($request->id_categoria);
                if($categoria){
                    if($request->hasFile('image')){
                        //pega o filename com a extensão
                        $filenameWithExt = $request->file('image')->getClientOriginalName();
                        // pega o filename sem extensão
                        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                        // pega apenas a extensão
                        $extension = $request->file('image')->getClientOriginalExtension();
                        // Salva o filename com o time
                        $fileNameToStore= $filename.'_'.time().'.'.$extension;
                        // Sobe a imagem no servidor
                        $path = $request->file('image')->storeAs('public/img', $fileNameToStore);
                        //----------------------------------------------------------------------
                        Storage::disk('public')->delete('img/'.$produto->image);
                        //---
                    } else {
                        $fileNameToStore = 'noimage.png'; //$produto->image
                    }
                    //save in database
                    Produto::where('id', $request->id )->update([
                        'name'            => $request->name,
                        'description'     => mb_strtolower($request->description),
                        'price'           => $request->price,
                        'date_expiration' => mb_strtolower($request->date_expiration),
                        'image'           => $fileNameToStore,
                        'id_categoria'    => $request->id_categoria
                    ]);
                    //---
                    return Response::successWithData("Produto atualizado com sucesso!", [
                        "produto" => Produto::find($request->id)
                    ]);
                }else{
                    return Response::error([
                        'categoria' => 'essa categoria não existe'
                    ]);
                }
            }else{
                return Response::error([
                    'produto' => 'esse produto não existe'
                ]);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return Response::error($e->errors());
        }
    }
}
