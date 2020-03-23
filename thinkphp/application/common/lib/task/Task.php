<?php
/**
 * 处理所有task异步任务
 * Created by PhpStorm.
 * User: BACK-LYJ
 * Date: 2020/3/18
 * Time: 20:15
 */

namespace app\common\lib\task;

use app\common\lib\ali\Sms;
use app\common\lib\redis\Predis;
use app\common\lib\Redis;

class Task
{
    /**
     * 异步发送验证码
     * @param $data
     */
    public function sendSms($data)
    {
        // try {
        //     $response = Sms::sendSms($data['phone'], $data['code']);
        // } catch (\Exception $e) {
        //     return false;
        // }

        $response = Sms::sendSms($data['phone'], $data['code']);
        if (!$response) {
            return false;
        }

        // 发送成功 则记录redis
        $res['Code'] = 'OK';
        if ($res['Code'] === 'OK') {
            // redis
            Predis::getInstance()->set(Redis::smsKey($data['phone']), $data['code'], config('redis.out_time'));
        } else {
            return false;
        }

        return true;
    }
}