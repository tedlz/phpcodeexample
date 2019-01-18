<?php

$ip = '127.0.0.1';
// $ip = '192.168.163.184';
$port = 20002;
// $port = 20000; //user
// $port = 20001; //rich
// $port = 20003; //184上测试

$body = [
    'cmdid' => 'Bullet.SendGift',
    // 'cmdid' => 'Gift.Use',
    // 'cmdid' => 'skillExperience',
    // 'uid' => 250,
    // 'exp' => 11,
    'uid' => 250,
    'gift' => 10001,
    'roomId' => 1917,
    'number' => 1,
    // 'roomid' => 874,
    // 'roomid' => 12,

    // 'pid' => 1,
    // 'count' => 1,
    // 'object' => 'a',
    // 'gid' => 1,
    // 'type' => 0,
    // 'time' => time(),
];

$s = microtime(true);

$body = json_encode($body, JSON_UNESCAPED_UNICODE);

$toType = $port;
$toIp = 0; //ip2long('192.168.163.110');0=随机一台，-1=所有
$fromType = $port;
$fromIp = 0;
$askId = 0;
$askId2 = 1000;
$head = pack('IsIsIII', strlen($body), $toType, $toIp, $fromType, $fromIp, $askId, $askId2);

$dataPack = $head . $body;

// $client = new swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_SYNC);

// $client -> set([
//     'open_length_check' => 1,
//     'package_length_type' => 'L',
//     'package_length_offset' => 0,
//     'package_body_offset' => 24,
//     'package_max_length' => 1000,
// ]);

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

echo "试图连接 '$ip' 端口 '$port'...\n";
$result = socket_connect($socket, $ip, $port);
if ($result === false) {
    echo "socket_connect() failed.\n";
} else {
    echo "连接OK\n";
}

$s = socket_write($socket, $dataPack, strlen($dataPack));

socket_recv($socket, $buf, 4, MSG_PEEK);

$len = unpack('Ilen', $buf);

var_dump($len);

$out = socket_read($socket, $len['len'] + 24);

$out = unpack('Ilen/stoType/ItoIp/sfromType/IfromIp/IaskId/IaskId2/A*body', $out);

print_r($out);

echo "关闭SOCKET...\n";
socket_close($socket);
echo "关闭OK\n";
