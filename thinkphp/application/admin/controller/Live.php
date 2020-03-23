<?php

namespace app\admin\controller;

use  app\common\lib\ali\Sms;
use app\common\lib\Util;

class Live
{
    public function push()
    {
        print_r($_GET);

        // 赛况信息入库 组装数据push到前端页面
        $_POST['http_server']->push(2, 'hello-from-server');
    }

}
