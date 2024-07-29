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

    public static function paginationProdutos($page){
        try{
            // 10 linhas por page
            /**
             * 1 - 10
             * 10 - 20
             * 20 - 30
             */
            $offset = ($page*10)-10;
            $count = DB::table('produto as p')->leftjoin('categoria as c', 'p.id_categoria', '=', 'c.id')->count();
            //---
            $records = DB::table('produto as p')->select(
                'p.id as id',
                'p.name as name',
                'p.description as description',
                'p.price as price',
                'p.date_expiration as date_expiration',
                'p.image as image',
                'p.id_categoria as id_categoria',
                'c.name as categoria_name'
            )
            ->leftjoin('categoria as c', 'p.id_categoria', '=', 'c.id')->offset($offset)->limit(10)->get();
            return [
                "records" => $records,
                "count" => $count
            ];
        }catch (\Illuminate\Database\QueryException $e) {
            return [
                'message' => $e->getMessage(),
            ];
        }
    }
}
