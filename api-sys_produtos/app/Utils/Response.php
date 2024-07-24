<?php

namespace App\Utils;

use Illuminate\Support\Facades\DB;

class Response extends BaseUtils
{

    public static function success($msg = null){
        try {
            return response()->json([
                "status" => 200,
                "info" => "success",
                "msg" => $msg,
            ], 200);
        } catch (\Exception $e) {
            return [
                'message' => $e->getMessage(),
            ];
        }
    }

    public static function successWithData($msg = null, $data = []){
        try {
            return response()->json([
                "status" => 200,
                "info" => "success",
                "msg" => $msg,
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            return [
                'message' => $e->getMessage(),
            ];
        }
    }

    public static function error($data = []){
        try {
            return response()->json([
                "status" => 400,
                "info" => "error",
                'data' => $data,
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                "status" => 400,
                "info" => "error",
                'data' => $e->getMessage(),
            ], 400);
        }
    }


}
