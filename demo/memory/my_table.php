<?php
/**
 * Created by PhpStorm.
 * User: BACK-LYJ
 * Date: 2020/3/10
 * Time: 19:59
 */

$table = new swoole_table(1024);

// 添加一列
$table->column('id',$table::TYPE_INT,4);
$table->column('name',$table::TYPE_STRING,64);
$table->column('age',$table::TYPE_INT,3);
$table->create();

$table->set('lintao',['id'=>1,'name'=>'lin','age'=>1]);
$table['hyh'] = [
    'id'=>2,
];

print_r($table['lintao']);
print_r($table['hyh']);