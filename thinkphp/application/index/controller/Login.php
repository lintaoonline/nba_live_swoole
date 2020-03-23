<?php
/**
 * Created by PhpStorm.
 * User: BACK-LYJ
 * Date: 2020/3/16
 * Time: 21:35
 */

namespace app\index\controller;

use app\common\lib\ali\Sms;
use app\common\lib\Redis;
use app\common\lib\redis\Predis;
use app\common\lib\Util;
use MongoDB\BSON\UTCDateTime;

class Login
{
    public function index()
    {
        $phone = intval($_GET['phone_num']);
        $code = intval($_GET['code']);
        if (empty($phone) || empty($code)) {
            Util::show(config('code.error'), '手机或验证码为空');
        }
        // $redisCode = Predis::getInstance()->get(Redis::smsKey($phone));
        try {
            $redisCode = Predis::getInstance()->get(Redis::smsKey($phone));
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        if ($redisCode == $code) {
            $data = [
                'user' => $phone,
                'srcKey' => md5(Redis::userKey($phone)),
                'time' => time(),
                'isLogin' => true,
            ];
            Predis::getInstance()->set(Redis::userKey($phone), $data);
            Util::show(config('code.success'), '登录成功', $data);
        } else {
            Util::show(config('code.error'), '登录失败');
        }

    }


}