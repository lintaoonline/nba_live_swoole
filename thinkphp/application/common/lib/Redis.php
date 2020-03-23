<?php
/**
 * Created by PhpStorm.
 * User: BACK-LYJ
 * Date: 2020/3/15
 * Time: 21:24
 */

namespace app\common\lib;
class Redis
{
    public static $pre = "sms_";
    public static $userpre = "user_";

    public static function smsKey($phone)
    {
        return self::$pre . $phone;
    }

    public static function userKey($phone)
    {
        return self::$userpre . $phone;
    }
}