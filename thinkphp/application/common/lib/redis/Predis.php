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
        $this->redis->auth('111111'); //设置密码
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

    /**
     * 添加有序集合
     * @param $key
     * @param $value
     * @return int
     */
    // public function sAdd($key, $value)
    // {
    //     return $this->redis->sAdd($key, $value);
    // }

    /**
     * 删除有序集合
     * @param $key
     * @param $value
     * @return int
     */
    // public function sRem($key, $value)
    // {
    //     return $this->redis->sRem($key, $value);
    // }

    /**
     * 获取有序集合
     * @param $key
     * @return array
     */
    public function sMembers($key)
    {
        return $this->redis->sMembers($key);
    }

    /**
     * 调用不存在的方法时 ，会执行该函数
     * @param $name 不存在的函数名
     * @param $arg  函数的参数，数组格式
     * @return bool
     */
    public function __call($name, $arg)
    {
        if (count($arg) != 2) {
            return false;
        }
        return $this->redis->$name($arg[0], $arg[1]);
    }
}
