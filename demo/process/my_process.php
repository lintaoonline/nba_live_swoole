<?php
/**
 * Created by PhpStorm.
 * User: BACK-LYJ
 * Date: 2020/3/8
 * Time: 11:22
 */

$process = new swoole_process(function (swoole_process $pro) {
    $pro->exec('/work/study/soft/php/bin/php', [__DIR__ . '/../server/http_server.php']);
}, false);
$pid = $process->start();
echo $pid . PHP_EOL;
echo __DIR__ . PHP_EOL;
swoole_process::wait();

