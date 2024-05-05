<?php

namespace App\Http;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

define("DECIMALPOINT", ".");
define("GROUPINGSEPARATOR", ",");

class Helper {
    public static function jsonResponse(bool $success = false, $message = "", $data = null) {
        return response()->json([
            "success" => $success,
            "message" => $message,
            "data" => $data
        ]);
    }

    public static function paginate(Builder $query, $page = 1, $limit = 10) {
        $data = (clone $query)
            ->offset($limit * ($page - 1))
            ->take($limit)
            ->get();
        $count = (clone $query)->count();

        return (object) [
            "pagination" => (object) [
                "maxRows" => $count,
                "page" => $page,
                "maxPage" => ceil($count / $limit),
                "limit" => $limit
            ],
            "data" => $data
        ];
    }

    
    public static function currency2Float ($value) {
        if (!isset($value)) return 0;

        $num = preg_replace('/'.GROUPINGSEPARATOR.'/', '', $value);
        $num = (float) $num;
        return $num;
    }
}