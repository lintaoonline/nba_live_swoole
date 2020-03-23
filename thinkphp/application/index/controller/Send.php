<?php

namespace app\index\controller;

use  app\common\lib\ali\Sms;
use app\common\lib\Util;
use app\common\lib\Redis;

class Send
{
    public function index()
    {

        $phone = intval($_GET['phone_num']);
        if (empty($phone)) {
            return Util::show(config('code.error'), 'error');
        }

        $code = rand(1000, 9999);
        // try{
        //     $response = Sms::sendSms($phone,$code);
        // }catch (\Exception $e){
        //     return Util::show(config('code.error'),'阿里短信内部异常');
        // }
        $taskData = [
            'method' => 'sendSms',
            'data' => [
                'phone' => $phone,
                'code' => $code,
            ],
        ];
        $_POST['http_server']->task($taskData);
        return Util::show(config('code.success'), 'ok');

        // $response['Code'] = 'OK';
        // if ($response['Code'] === 'OK') {
        //     // redis
        //     $redis = new \Swoole\Coroutine\Redis();
        //     $redis->connect(config('redis.host'), config('redis.port'));
        //     $redis->set(Redis::smsKey($phone), $code, config('redis.out_time'));
        //     return Util::show(config('code.success'), '发送成功');
        // } else {
        //     return Util::show(config('code.error'), '发送失败');
        // }

    }


}
