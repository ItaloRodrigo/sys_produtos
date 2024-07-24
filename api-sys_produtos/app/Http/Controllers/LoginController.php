<?php

namespace App\Http\Controllers;

use App\Models\PersonalAccessToken;
use App\Models\User;
use App\Utils\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    /**
     * Auth Controller
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="Auth login",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
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

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->input('remember');
        //---
        if (Auth::attempt($credentials,$remember)) {
            $user = $request->user();
            //---
            if($this->taLogado($user->id)['logado']){
                User::find($user->id)->tokens()->delete();
            }
            return Response::successWithData('Login efetuado com sucesso!',[
                "logado" => true,
                "user"   => $user,
                "token"  => $user->createToken("token")->plainTextToken,
                "tokens" => User::find($user->id)->tokens(),
            ]);
        }else{
            return Response::error([
                "logado" => false,
            ]);
        }
    }

    /**
     * Auth Controller
     * @OA\Get(
     *     path="/api/auth/isloged",
     *     summary="Auth loged",
     *     tags={"Auth"},
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

    public function isLoged(Request $request,$id){
        // $id = $request->input('id');

        if($this->taLogado($id)['logado']){
            return Response::successWithData('Usuário logado',[
                "logado" => true,
                "user"   => $request->user(),
                "token"  => $request->user()->currentAccessToken(),
                "tokens" => $this->taLogado($id)['tokens']
            ]);
        }else{
            return Response::error([
                'logado' => false,
                'message' => 'Usuário não está logado!'
            ]);
        }
    }

    private function taLogado($id){
        $tokens = PersonalAccessToken::where("tokenable_id",$id)->get();
        //---
        return [
            "logado" =>(count($tokens) > 0)?true:false,
            "tokens" => $tokens
        ];
    }

    /**
     * Auth Controller
     * @OA\Post(
     *     path="/api/auth/logout",
     *     summary="Auth logout",
     *     tags={"Auth"},
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

    public function logout(Request $request){
        $id = $request->input('id');
        //---
        User::find($id)->tokens()->delete();
        //---
        return Response::successWithData('Logout realizado com sucesso!',[
            "logout" => true
        ]);
    }
}
