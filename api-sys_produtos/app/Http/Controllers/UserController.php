<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Utils\Pagination;
use App\Utils\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

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
