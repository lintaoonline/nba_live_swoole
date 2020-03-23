<?php
/**
 * Created by PhpStorm.
 * User: BACK-LYJ
 * Date: 2020/3/15
 * Time: 20:45
 */

namespace app\common\lib;

class  Util
{
    public static function show($status, $info = '', $data = [])
    {
        $res = [
            'status' => $status,
            'info' => $info,
            'data' => $data,
        ];
        echo json_encode($res);
    }
}