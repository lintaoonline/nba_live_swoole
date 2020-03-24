<?php

namespace app\admin\controller;

use app\common\lib\redis\Predis;
use app\common\lib\Util;

class Live
{
    public function push()
    {
        print_r($_GET);

        // 官方获取连接的用户
        // foreach ($server->connections as $fd) {
        //     var_dump($fd);
        // }
        // echo "当前服务器共有 " . count($server->connections) . " 个连接\n";

        // 赛况信息入库 组装数据push到前端页面 客户端连接的 ID 【如果指定的 $fd 对应的 TCP 连接并非 websocket 客户端，将会发送失败】
        // 测试时写死
        // $_POST['http_server']->push(3, 'hello-from-server');


        $clients = Predis::getInstance()->sMembers(config('redis.live_game_key'));
        print_r($clients);
        foreach ($clients as $fd) {
            $_POST['http_server']->push($fd,'hello1234');
        }
    }

}
