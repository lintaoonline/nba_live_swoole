<?php
/**
 * Created by PhpStorm.
 * User: BACK-LYJ
 * Date: 2020/2/22
 * Time: 9:43
 */

$http = new swoole_http_server('0.0.0.0', 8811);

$http->on('request', function ($request, $response) {
    $response->end('hhaha');
});

$http->start();
