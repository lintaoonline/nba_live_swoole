<?php
/**
 * Created by PhpStorm.
 * User: BACK-LYJ
 * Date: 2020/3/17
 * Time: 21:42
 */

class http
{
    CONST HOST = "0.0.0.0";
    CONST PORT = 8811;

    public $http = null;

    public function __construct()
    {
        $this->http = new swoole_http_server(self::HOST, self::PORT);

        $this->http->set(
            [
                'enable_static_handler' => true,
                'document_root' => "/data/www/swoole/thinkphp/public/static",
                'worker_num' => 5,
                'task_worker_num' => 4,
            ]
        );
        $this->http->on("workerstart", [$this, 'onWorkerStart']);
        $this->http->on("request", [$this, 'onRequest']);
        $this->http->on("task", [$this, 'onTask']);
        $this->http->on("finish", [$this, 'onFinish']);
        $this->http->on("close", [$this, 'onClose']);

        $this->http->start();
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
        $_POST = [];
        if (isset($request->post)) {
            foreach ($request->post as $k => $v) {
                $_POST[$k] = $v;
            }
        }
        $_POST['http_server'] = $this->http;

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
        $flag = $obj->$method($data['data']);

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
     * close
     * @param $ws
     * @param $fd
     */
    public function onClose($ws, $fd)
    {
        echo "clientid:{$fd}\n";
    }
}

new Http();