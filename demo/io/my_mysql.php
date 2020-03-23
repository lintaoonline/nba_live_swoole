<?php
/**
 * Created by PhpStorm.
 * User: BACK-LYJ
 * Date: 2020/3/3
 * Time: 21:17
 */

class AysMysql
{
    public $db = '';
    public $db_config = [];

    public function __construct()
    {
        $this->db = new Swoole\Mysql;
        $this->db_config = [
            'host' => '127.0.0.1',
            'port' => 3306,
            'user' => 'root',
            'password' => 123456,
            'database' => 'test',
            'charset' => 'utf8',
        ];
    }

    public function execute($id, $name)
    {
        echo '1' . PHP_EOL;
        $this->db->connect($this->db_config, function ($db, $result) {
            echo '2' . PHP_EOL;
            if ($result === false) {
                var_dump($db->connect_error);
            }

            $sql = "select * from user where id = 1";
            // 耗时操作
            $db->query($sql, function ($db, $result) {
                echo '3' . PHP_EOL;
                if ($result === false) {
                    var_dump($db->error);
                } else if ($result === true) {
                    var_dump($db->affected_rows);
                } else {
                    echo '4' . PHP_EOL;
                    print_r($result);
                }
            });
            $db->close();
            echo '5' . PHP_EOL;
        });
        // echo '6' . PHP_EOL;
        return true;
    }
}

// 输出顺序为 1 true 2534 数组打印
$obj = new AysMysql();
$flag = $obj->execute(1, 'singwa-111112');
var_dump($flag) . PHP_EOL;
echo "start" . PHP_EOL;