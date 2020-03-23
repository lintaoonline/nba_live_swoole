<?php
/**
 * Created by PhpStorm.
 * User: BACK-LYJ
 * Date: 2020/3/16
 * Time: 21:11
 */

namespace app\common\lib\redis;

class Predis
{
    public $redis = '';
    private static $_instance = null;

    public static function getInstance()
    {
        if (empty(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct()
    {
        $this->redis = new \Redis();
        $result = $this->redis->connect(config('redis.host'), config('redis.port'), config('redis.time_out'));
        if ($result === false) {
            throw new \Exception('redis connect error');
        }

    }

    public function set($key, $value, $time = 0)
    {
        if (!$key) {
            return '';
        }
        if (is_array($value)) {
            $value = json_encode($value);
        }
        if (!$time) {
            return $this->redis->set($key, $value);
        }

        return $this->redis->setex($key, $time, $value);
    }

    public function get($key)
    {
        if (!$key) {
            return '';
        }
        return $this->redis->get($key);
    }
}