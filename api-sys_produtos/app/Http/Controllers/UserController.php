<?php

namespace App\Http\Controllers;

use App\Models\PersonalAccessToken;
use App\Models\User;
use App\Rules\RuleRequired;
use App\Rules\RuleStringMax;
use App\Utils\Pagination;
use App\Utils\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    /**
     * Usuário Controller
     * @OA\Get(
     *     path="/api/user/get/{id}",
     *     summary="Get Usuário",
     *     tags={"Usuário"},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * ),
     */

     public function get($id){
        try {
            return Response::successWithData("ok", [
                "user" => User::find($id)
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return Response::error($e->errors());
        }
    }

    /**
     * Usuário Controller
     * @OA\Get(
     *     path="/api/user/all",
     *     summary="All Usuário",
     *     tags={"Usuário"},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * ),
     */

     public function all(){
        try {
            return Response::successWithData("ok", [
                "users" => User::all()
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return Response::error($e->errors());
        }
    }

   /**
     * Usuário Controller
     * @OA\Get(
     *     path="/api/user/create",
     *     summary="Create Usuário",
     *     tags={"Usuário"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
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
                'name'     => [new RuleRequired, new RuleStringMax(255)],
                'email'    => [new RuleRequired, new RuleStringMax(255)],
                'password' => [new RuleRequired, new RuleStringMax(255)],
            ]);

            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);
            return Response::successWithData('Usuário criado com sucesso!', [
                'user' => User::find($user->id)
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return Response::error($e->errors());
        }
    }

   /**
     * Usuário Controller
     * @OA\Get(
     *     path="/api/user/update",
     *     summary="Update Usuário",
     *     tags={"Usuário"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
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
                'id'       => [new RuleRequired],
                'name'     => [new RuleRequired, new RuleStringMax(255)],
                'email'    => [new RuleRequired, new RuleStringMax(255)],
                'password' => [new RuleRequired, new RuleStringMax(255)],
            ]);

            $user = User::where('id',$request->id)->update([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);
            return Response::successWithData('Usuário atualizado com sucesso!', [
                'user' => User::find($user->id)
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return Response::error($e->errors());
        }
    }

   /**
     * Usuário Controller
     * @OA\Get(
     *     path="/api/user/delete",
     *     summary="Delete Usuário",
     *     tags={"Usuário"},
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
            $existe = User::find($id);
            if($existe){
                PersonalAccessToken::where("tokenable_id",$id)->delete();
                //---
                User::where("id", $id)->delete();
                //---
                return Response::success('Usuário deletado com sucesso!');
            }else{
                return Response::error([
                    'usuario' => 'esse usuário não existe!'
                ]);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return Response::error($e->errors());
        }
    }

    /**
     * Usuário Controller
     * @OA\Post(
     *     path="/api/user/pagination",
     *     summary="Pagination Usuário",
     *     tags={"Usuário"},
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

    public function pagination(Request $request,$page){
        try {
            // 10 linhas por page
            /**
             * 1 - 10
             * 10 - 20
             * 20 - 30
             */
            $pagination = Pagination::paginationUser($page);
            //---
            return response()->json([
                "status" => 200,
                "info" => "success",
                "users" => $pagination['records'],
                "count" => $pagination['count']
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                "status" => 400,
                "info" => "error",
                "erros" => $e->errors(),
            ], 400);
        }
    }
}
