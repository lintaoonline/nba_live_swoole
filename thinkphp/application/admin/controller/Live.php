<?php

namespace app\admin\controller;

use app\common\lib\redis\Predis;
use app\common\lib\Util;

class Live
{
    public function push()
    {
        // print_r($_GET);

        // 官方获取连接的用户
        // foreach ($server->connections as $fd) {
        //     var_dump($fd);
        // }
        // echo "当前服务器共有 " . count($server->connections) . " 个连接\n";

        // 赛况信息入库 组装数据push到前端页面 客户端连接的 ID 【如果指定的 $fd 对应的 TCP 连接并非 websocket 客户端，将会发送失败】
        // 测试时写死
        // $_POST['http_server']->push(3, 'hello-from-server');


        if (empty($_GET)) {
            return Util::show(config('code.error'), 'error');
        }

        $teams = [
            1 => [
                'name' => '马刺',
                'logo' => '/live/imgs/team1.png',
            ],
            4 => [
                'name' => '火箭',
                'logo' => '/live/imgs/team2.png',
            ],
        ];

        $data = [
            'type' => intval($_GET['type']),
            'title' => !empty($teams[$_GET['team_id']]) ? $teams[$_GET['team_id']]['name'] : '直播员',
            'logo' => !empty($teams[$_GET['team_id']]) ? $teams[$_GET['team_id']]['logo'] : '',
            'content' => !empty($_GET['content']) ? $_GET['content'] : '',
            'image' => !empty($_GET['image']) ? $_GET['image'] : '',
        ];

        $taskData = [
            'method' => 'pushLive',
            'data' => $data,
        ];

        $_POST['http_server']->task($taskData);
        return Util::show(config('code.success'), 'ok');


    }

}
