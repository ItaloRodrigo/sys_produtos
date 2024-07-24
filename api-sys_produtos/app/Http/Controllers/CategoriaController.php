<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Produto;
use App\Rules\RuleRequired;
use App\Rules\RuleStringMax;
use App\Utils\Pagination;
use App\Utils\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriaController extends Controller
{

    /**
     * Categorias Controller
     * @OA\Get(
     *     path="/api/categoria/get/{id}",
     *     summary="Get Categoria",
     *     tags={"Categoria"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="id",
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

    public function get($id){
        try {
            $categoria = Categoria::find($id);
            //---
            return Response::successWithData('ok', [
                'categoria' => $categoria
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return Response::error($e->errors());
        }
    }

    /**
     * Categorias Controller
     * @OA\Get(
     *     path="/api/categoria/all",
     *     summary="All Categoria",
     *     tags={"Categoria"},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * ),
     */

    public function all(){
        try {
            return Response::successWithData('ok', [
                'categorias' => Categoria::all()
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return Response::error($e->errors());
        }
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
     *                     property="id",
     *                     type="int"
     *                 ),
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
                'name'             => [new RuleRequired, new RuleStringMax(100)],
            ]);

            $categoria = Categoria::create([
                'name' => $request->name,
            ]);
            return Response::successWithData('Categoria criada com sucesso!', [
                'categoria' => $categoria
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return Response::error($e->errors());
        }
    }

    /**
     * Categorias Controller
     * @OA\Post(
     *     path="/api/categoria/update",
     *     summary="Update Categoria",
     *     tags={"Categoria"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="id",
     *                     type="int"
     *                 ),
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

    public function update(Request $request){
        try {
            $request->validate([
                'id'     => [new RuleRequired],
                'name'   => [new RuleRequired, new RuleStringMax(100)],
            ]);
            //---
            Categoria::where('id',$request->id)->update([
                'name' => $request->name,
            ]);
            //---
            return Response::successWithData('Categoria atualizada com sucesso!', [
                'categoria' => Categoria::find($request->id)
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return Response::error($e->errors());
        }
    }

    /**
     * Categorias Controller
     * @OA\Post(
     *     path="/api/categoria/delete",
     *     summary="Delete Categoria",
     *     tags={"Categoria"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="id",
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

    public function delete($id){
        try {
            $existe = Categoria::find($id);
            if($existe){
                //deletar as dependências com a tabela produto
                Produto::where("id_categoria", $id)->delete();
                //deletar o registro de categoria
                Categoria::where('id',$id)->delete();
                //---
                return Response::success('Categoria deletada com sucesso!');
            }else{
                return Response::error([
                    'categoria' => 'essa categoria não existe!'
                ]);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return Response::error($e->errors());
        }
    }

    /**
     * Categorias Controller
     * @OA\Post(
     *     path="/api/categoria/pagination",
     *     summary="Pagination Categoria",
     *     tags={"Categoria"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="page",
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

    public function pagination($page){
        try {
            // 10 linhas por page
            /**
             * 1 - 10
             * 10 - 20
             * 20 - 30
             */

            $pagination = Pagination::pagination($page,"categoria");
            //---
            return Response::successWithData('ok', [
                "categorias" => $pagination['records'],
                "count"      => $pagination['count']
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return Response::error($e->errors());
        }
    }
}
