<?php

namespace App\Utils;

use Illuminate\Support\Facades\DB;

class Pagination extends BaseUtils
{

    public static function pagination($page, $table){
        try {
            // 10 linhas por page
            /**
             * 1 - 10
             * 10 - 20
             * 20 - 30
             */
            $offset = ($page*10)-10;
            $count = DB::table($table)->count();
            //---
            $records = DB::table($table)
                            ->offset($offset)->limit(10)->get();
            return [
                "records" => $records,
                "count" => $count
            ];
        } catch (\Illuminate\Database\QueryException $e) {
            return [
                'message' => $e->getMessage(),
            ];
        }
    }

    public static function paginationUser($page){
        try {
            // 10 linhas por page
            /**
             * 1 - 10
             * 10 - 20
             * 20 - 30
             */
            $offset = ($page*10)-10;
            $count = DB::table('users')->count();
            //---
            $records = DB::table("users")
                            ->select('*',(
                                DB::raw('(
                                    select date_format(last_used_at,"%d/%m/%Y %h:%i:%s") from personal_access_tokens as p
                                    where p.tokenable_id = users.id
                                ) as last_access')
                            ))
                            ->offset($offset)->limit(10)->get();
            return [
                "records" => $records,
                "count" => $count
            ];
        } catch (\Illuminate\Database\QueryException $e) {
            return [
                'message' => $e->getMessage(),
            ];
        }
    }
}
