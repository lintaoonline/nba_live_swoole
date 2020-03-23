<?php

class Ws
{
// const HOST = '0.0.0.0';
// const PORT = 8812;
    public $ws = NULL;

    public function __construct($host = '0.0.0.0', $port = 8812)
    {
        $this->ws = new swoole_websocket_server($host, $port);
        $this->ws->set([
//            'enable_static_handler' => true,
//            'document_root' => "/data/www/swoole/demo/data",
            'worker_num' => 2,
            'task_worker_num' => 2,
        ]);

        $this->ws->on('open', [$this, 'onOpen']);
        $this->ws->on('message', [$this, 'onMessage']);
        $this->ws->on('task', [$this, 'onTask']);
        $this->ws->on('finish', [$this, 'onFinish']);
        $this->ws->on('close', [$this, 'onClose']);
        $this->ws->start();
    }

    public function onOpen($ws, $request)
    {
        print_r('onOpen-fd is :' . $request->fd . "\r\n");
        swoole_timer_tick(20, function ($id) {
            echo "2s TimeId:{$id}-{time()}\n";
        });

        // if ($request->fd == 1) {
        //     // 每2秒执行
        //     swoole_timer_tick(2000, function ($timer_id) {
        //         echo "2s: timerId:{$timer_id}\n";
        //     });
        // }
    }

    public function onMessage($ws, $frame)
    {
        echo 'onMessage-ser-push-message' . $frame->data . "\r\n";
        $data = [
            'task' => 100,
            'fd' => $frame->fd,
        ];
        swoole_timer_after(5000,function () use($ws,$frame){
            echo "5s-after\n";
            $ws->push($frame->fd, "server-push:" . date('Y-m-d H:i:s'));
        });
        // 异步耗时任务
        // $ws->task($data);
        // echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
        // 此处无须等待10s，服务端会立即发消息给客户端
        $ws->push($frame->fd, "server-push:" . date('Y-m-d H:i:s'));
    }

    public function onTask($serv, $task_id, $worker_id, $data)
    {
        print_r($data);
        // 耗时场景 10s
        sleep(10);
        return "on task finish" . date('Y-m-d H:i:s'); // 告诉worker
    }

    // 此处的data为ontask里 return的内容
    public function onFinish($serv, $taskId, $data)
    {
        echo "from-ser-taskId:{$taskId}\n";
        echo "from-ser-finish-data-sucess:{$data}\n";
    }

    public function onClose($ws, $fd)
    {
        echo "client {$fd} closed\n";
    }
}

$obj = new Ws('0.0.0.0', 8812);
