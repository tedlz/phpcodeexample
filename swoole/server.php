<?php

$serv = new swoole_server('0.0.0.0', 9501);

$serv->set([
    'task_worker_num' => 2, // 设置启动两个task进程
]);

// 绑定回调函数
$serv->on('Task', 'onTask');
$serv->on('Finish', 'onFinish');
$serv->on('Receive', 'onReceive');

function onTask($serv, $task_id, $from_id, $data)
{
    echo 'Task: ', $data;
}

function onFinish($serv, $task_id, $data)
{
    echo 'Finish: ', $data;
}

function onReceive($serv, $data)
{
    echo 'Receive: ', $data;
}

$serv->start();
