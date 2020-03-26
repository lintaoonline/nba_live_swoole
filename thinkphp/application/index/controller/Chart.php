<?php

namespace app\index\controller;

use  app\common\lib\ali\Sms;
use app\common\lib\Util;

class Chart
{
    public function index()
    {
        // 登录操作
        if (empty($_POST['game_id'])) {
            return Util::show(config('code.error'), '场次错误');
        }

        if (empty($_POST['content'])) {
            return Util::show(config('code.error'), '内容为空');
        }
        // var_dump($_POST['http_server']);
        // var_dump($_POST['http_server']->fd);
        $data = [
            'user' => '用户' . rand(0, 20),
            'content' => $_POST['content']
        ];
        // 从数据库中取出最近的几条记录 发给前端
        foreach ($_POST['http_server']->ports[1]->connections as $fd) {
            $_POST['http_server']->push($fd,json_encode($data));
        }
        return Util::show(config('code.success'), 'ok',$data);
    }

}
