<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Produto;
use App\Rules\RuleRequired;
use App\Rules\RuleStringMax;
use App\Rules\RuleValidateDate;
use App\Rules\RuleValuePositive;
use App\Utils\Pagination;
use App\Utils\Response;
use Illuminate\Http\Request;
use File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProdutoController extends Controller
{

    /**
     * Produtos Controller
     * @OA\Get(
     *     path="/api/produto/get/{id}",
     *     summary="Get Produto",
     *     tags={"Produto"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer")
     *         )
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
            $produtos = DB::table('produto as p')->select(
                'p.id as id',
                'p.name as name',
                'p.description as description',
                'p.price as price',
                'p.date_expiration as date_expiration',
                'p.image as image',
                'p.id_categoria as id_categoria',
                'c.name as categoria_name'
            )
            ->leftjoin('categoria as c', 'p.id_categoria', '=', 'c.id')->get();
            //---
            return Response::successWithData("ok", [
                "produtos" => $produtos
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
            Log::info('Request Data:', $request->all());

            $request->validate([
                'name'            => [new RuleRequired, new RuleStringMax(50)],
                'description'     => [new RuleRequired, new RuleStringMax(200)],
                'price'           => [new RuleRequired, new RuleValuePositive],
                'date_expiration' => [new RuleRequired, new RuleValidateDate, 'date_format:Y-m-d H:i:s'],
                'image'           => [new RuleRequired],
                'id_categoria'    => [new RuleRequired],
            ]);

            // Verifica se a categoria existe
            $categoria = Categoria::find($request->id_categoria);
            if (!$categoria) {
                return Response::error(['categoria' => 'Essa categoria não existe']);
            }
            //---
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filenameWithExt = $file->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $path = $file->storeAs('public/img', $fileNameToStore);
            } else {
                $fileNameToStore = 'noimage.png';
            }

            // Criação do produto
            $produto = Produto::create([
                'name'            => $request->name,
                'description'     => mb_strtolower($request->description),
                'price'           => $request->price,
                'date_expiration' => $request->date_expiration,
                'image'           => $fileNameToStore,
                'id_categoria'    => $request->id_categoria,
            ]);

            // Retorno de sucesso
            return Response::successWithData("Produto adicionado com sucesso!", [
                "produto" => $produto
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation Error:', $e->errors());
            return Response::error($e->errors());
        } catch (\Exception $e) {
            Log::error('General Error:', ['message' => $e->getMessage()]);
            return Response::error(['error' => 'Ocorreu um erro ao adicionar o produto.'.$e->getMessage()]);
        }
    }

    /**
     * Produtos Controller
     * @OA\Post(
     *     path="/api/produto/update",
     *     summary="Update Produto",
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

     public function update(Request $request, $id) {
        try {
            Log::info('Request Data:', $request->all());

            // Validação dos dados
            $request->validate([
                'name'            => [new RuleRequired, new RuleStringMax(50)],
                'description'     => [new RuleRequired, new RuleStringMax(200)],
                'price'           => [new RuleRequired, new RuleValuePositive],
                'date_expiration' => [new RuleRequired, new RuleValidateDate, 'date_format:Y-m-d H:i:s'],
                'image'           => [new RuleRequired], // O campo imagem é opcional na atualização
                'id_categoria'    => [new RuleRequired],
            ]);

            // Verifica se a categoria existe
            $categoria = Categoria::find($request->id_categoria);
            if (!$categoria) {
                return Response::error(['categoria' => 'Essa categoria não existe']);
            }

            // Localiza o produto
            $produto = Produto::find($id);
            if (!$produto) {
                return Response::error(['produto' => 'Produto não encontrado']);
            }

            // Atualiza os dados do produto
            $produto->name = $request->name;
            $produto->description = mb_strtolower($request->description);
            $produto->price = $request->price;
            $produto->date_expiration = $request->date_expiration;
            $produto->id_categoria = $request->id_categoria;

            // Atualiza a imagem se fornecida
            if ($request->hasFile('image')) {
                // Remove a imagem antiga se não for a padrão
                if ($produto->image && $produto->image !== 'noimage.png') {
                    $oldImagePath = storage_path('app/public/img/' . $produto->image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                // Salva a nova imagem
                $file = $request->file('image');
                $filenameWithExt = $file->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $path = $file->storeAs('public/img', $fileNameToStore);
                $produto->image = $fileNameToStore;
            }

            // Salva as alterações
            $produto->save();

            // Retorno de sucesso
            return Response::successWithData("Produto atualizado com sucesso!", [
                "produto" => $produto
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation Error:', $e->errors());
            return Response::error($e->errors());
        } catch (\Exception $e) {
            Log::error('General Error:', ['message' => $e->getMessage()]);
            return Response::error(['error' => 'Ocorreu um erro ao atualizar o produto.'.$e->getMessage()]);
        }
    }



    /**
     * Produtos Controller
     * @OA\Get(
     *     path="/api/produto/delete/{id}",
     *     summary="Delete Produto",
     *     tags={"Produto"},
     *     @OA\Parameter(
     *         name="id",
     *         in="id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * ),
     */

    public function delete($id){
        try {
            try {
                $existe = Produto::find($id);
                if($existe){
                    Produto::where("id_categoria", $id)->delete();
                    //---
                    return Response::success('Produto deletado com sucesso!');
                }else{
                    return Response::error([
                        'produto' => 'esse produto não existe!'
                    ]);
                }
            } catch (\Illuminate\Validation\ValidationException $e) {
                return Response::error($e->errors());
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return Response::error($e->errors());
        }
    }

    /**
     * Produtos Controller
     * @OA\Post(
     *     path="/api/produto/pagination/{page}",
     *     summary="Pagination Produto",
     *     tags={"Produto"},
     *     @OA\Parameter(
     *         name="page",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
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

            $pagination = Pagination::paginationProdutos($page);
            //---
            return Response::successWithData('ok', [
                "produtos"   => $pagination["records"],
                "count"      => $pagination['count']
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return Response::error($e->errors());
        }
    }
}
