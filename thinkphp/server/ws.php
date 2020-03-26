<?php
/**
 * Created by PhpStorm.
 * User: BACK-LYJ
 * Date: 2020/3/17
 * Time: 21:42
 */

class Ws
{
    CONST HOST = "0.0.0.0";
    CONST PORT = 8811;
    CONST CHART_PORT = 8812;

    public $http = null;

    public function __construct()
    {
        $this->ws = new swoole_websocket_server(self::HOST, self::PORT);
        // 多端口监听
        $this->ws->listen(self::HOST, self::CHART_PORT,SWOOLE_SOCK_TCP);
        // 重启时 获取sMember中的数据 有值得话则删除
        $this->ws->set(
            [
                'enable_static_handler' => true,
                'document_root' => "/data/www/swoole/thinkphp/public/static",
                'worker_num' => 5,
                'task_worker_num' => 4,
            ]
        );
        $this->ws->on("open", [$this, 'onOpen']);
        $this->ws->on("message", [$this, 'onMessage']);
        $this->ws->on("workerstart", [$this, 'onWorkerStart']);
        $this->ws->on("request", [$this, 'onRequest']);
        $this->ws->on("task", [$this, 'onTask']);
        $this->ws->on("finish", [$this, 'onFinish']);
        $this->ws->on("close", [$this, 'onClose']);

        $this->ws->start();
    }

    /**
     * 回调
     * @param swoole_server $server
     * @param $worker_id
     */
    public function onWorkerStart($server, $worker_id)
    {
        define('APP_PATH', __DIR__ . '/../application/');
        // 加载框架文件 自定义执行应用。如果直接引入start.php 则会直接执行程序
        // require __DIR__ . '/../thinkphp/base.php';
        // WorkerStart时引入start.php 可以在ontask里面使用tp的核心文件
        require __DIR__ . '/../thinkphp/start.php';
    }

    /**
     * request回调
     * @param $request
     * @param $response
     */
    public function onRequest($request, $response)
    {
        $_SERVER = [];
        if (isset($request->server)) {
            foreach ($request->server as $k => $v) {
                $_SERVER[strtoupper($k)] = $v;
            }
        }

        if (isset($request->header)) {
            foreach ($request->header as $k => $v) {
                $_SERVER[strtoupper($k)] = $v;
            }
        }
        // 超全局变量在swoole中不会被注销 define定义的也不会被注销 die exit也是
        // if (!empty($_GET)) {
        //     unset($_GET);
        // }
        $_GET = [];
        if (isset($request->get)) {
            foreach ($request->get as $k => $v) {
                $_GET[$k] = $v;
            }
        }
        $_FILES = [];
        if (isset($request->files)) {
            foreach ($request->files as $k => $v) {
                $_FILES[$k] = $v;
            }
        }
        $_POST = [];
        if (isset($request->post)) {
            foreach ($request->post as $k => $v) {
                $_POST[$k] = $v;
            }
        }
        $_POST['http_server'] = $this->ws;

        ob_start();
        try {
            think\Container::get('app', [APP_PATH])->run()->send();
        } catch (\Exceptio $e) {
            // todo
        }

        $res = ob_get_contents();
        ob_end_clean();
        //$response->cookie("singwa", "xsssss", time() + 1800);
        $response->end($res);
    }


    /**
     * @param $serv
     * @param $taskId
     * @param $workerId
     * @param $data
     */
    public function onTask($serv, $taskId, $workerId, $data)
    {
        // 分发task任务
        $obj = new app\common\lib\task\Task;
        $method = $data['method'];
        if (empty($method)) {
            return false;
        }
        $flag = $obj->$method($data['data'],$serv);

        return $flag; // 告诉worker
    }

    /**
     * @param $serv
     * @param $taskId
     * @param $data
     */
    public function onFinish($serv, $taskId, $data)
    {
        echo "taskId:{$taskId}\n";
        echo "finish-data-sucess:{$data}\n";
    }

    /**
     * 监听ws连接事件
     * @param $ws
     * @param $request
     */
    public function onOpen($ws, $request)
    {
        // print_r($ws);
        // 把客户端fd存入redis
        \app\common\lib\redis\Predis::getInstance()->sAdd(config('redis.live_game_key'),$request->fd);
        print_r('onOpen-fd is :' . $request->fd . "\r\n");
    }

    /**
     * 监听ws消息事件
     * @param $ws
     * @param $frame
     */
    public function onMessage($ws, $frame)
    {
        // echo 'onMessage-ser-push-message' . $frame->data . "\r\n";
        // $ws->push($frame->fd, "server-push:" . date('Y-m-d H:i:s'));
    }

    /**
     * close
     * @param $ws
     * @param $fd
     */
    public function onClose($ws, $fd)
    {
        \app\common\lib\redis\Predis::getInstance()->sRem(config('redis.live_game_key'),$fd);
        // 删除redis对应已连接的fd
        // echo "clientid:{$fd}\n";
    }
}

new Ws();