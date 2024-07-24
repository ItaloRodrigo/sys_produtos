<?php

namespace App\Http\Controllers;

use App\Utils\Pagination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{



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
