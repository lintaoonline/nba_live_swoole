<?php
/**
 * Created by PhpStorm.
 * User: baidu
 * Date: 18/2/28
 * Time: 上午1:39
 */

$http = new swoole_http_server("0.0.0.0", 8811);

$http->set(
    [
        'enable_static_handler' => true,
        'document_root' => "/data/www/swoole/thinkphp/public/static",
        'worker_num' => 5,
    ]
);
$http->on('WorkerStart', function (swoole_server $server, $worker_id) {
    // 此处引入，修改php代码需要重启服务
    // 定义应用目录
    define('APP_PATH', __DIR__ . '/../application/');
    // 加载框架文件 自定义执行应用。如果直接引入start.php 则会直接执行程序
    require __DIR__ . '/../thinkphp/base.php';
    // require __DIR__ . '/../thinkphp/start.php';
});
$http->on('request', function ($request, $response) use ($http) {
    // 此处引入，修改php代码后无需重启
    // define('APP_PATH', __DIR__ . '/../application/');
    // require __DIR__ . '/../thinkphp/base.php';
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
    // $http->close($http->worker_id);
    // $http->close($http->worker_id);
});

$http->start();

// topthink/think-swoole