<?php

namespace App\Http\Controllers;

use App\Models\PersonalAccessToken;
use App\Models\User;
use App\Utils\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->input('remember');
        //---
        if (Auth::attempt($credentials,$remember)) {
            $user = $request->user();
            //---
            if($this->taLogado($user->id)['status']){
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

    private function taLogado($id){
        $tokens = PersonalAccessToken::where("tokenable_id",$id)->get();
        //---
        return [
            "status" =>(count($tokens) > 0)?true:false,
            "tokens" => $tokens
        ];
    }

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
