<?php
/**
 * Created by PhpStorm.
 * User: BACK-LYJ
 * Date: 2020/3/8
 * Time: 20:18
 */

echo "process-start-time:" . date("Ymd H:i:s");
$workers = [];
$urls = [
    'http://baidu.com',
    'http://sina.com.cn',
    'http://qq.com',
    'http://baidu.com?search=singwa',
    'http://baidu.com?search=singwa2',
    'http://baidu.com?search=imooc',
];

for ($i = 0; $i < 6; $i++) {
    $pro = new swoole_process(function (swoole_process $worker) use ($i, $urls) {
        $content = curldata($urls[$i], $i);
        $worker->write($content . PHP_EOL);
    }, true);
    $pid = $pro->start();
    $workers[$pid] = $pro;
}

foreach ($workers as $pro) {
    echo $pro->read();
}

function curlData($url, $i = 1)
{
    // curl file_get_contents
    sleep(999);
    return $url . "success i = " . $i . PHP_EOL;
}

echo "process-end-time:" . date("H:i:s");