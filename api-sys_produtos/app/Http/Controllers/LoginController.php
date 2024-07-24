<?php

namespace App\Http\Controllers;

use App\Models\PersonalAccessToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function auth(Request $request)
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
            return response()->json([
                "status" => 200,
                "info" => "success",
                "logged" => true,
                "user" => $user,
                "token" => $user->createToken("token")->plainTextToken,
                "tokens" => User::find($user->id)->tokens(),
                "message" => "ok"
            ],200);
        }else{
            return response()->json([
                    // "credentials" => $credentials,
                    "logged" => false,
                    "message" => "NOK"
                ],
                200
            );
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
}
