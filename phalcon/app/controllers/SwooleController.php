<?php

namespace App\Controllers;

use Phalcon\Mvc\Controller;

class SwooleController extends Controller
{
    public function indexAction()
    {
        $body = [
            'cmdid' => 'getByUidRoomId',
            'uid' => 250,
            'roomId' => 874,
        ];

        $s = microtime(true);

        $body = json_encode($body, JSON_UNESCAPED_UNICODE);

        $toType = 20006;
        $toIp = 22222;//ip2long('192.168.163.110');
        $fromType = 20003;
        $fromIp = 1111111;
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

        $ip = '127.0.0.1';
        $port = 20000;
        echo "试图连接 '$ip' 端口 '$port'...\n";
        $result = socket_connect($socket, $ip, $port);
        if ($result === false) {
            echo "socket_connect() failed.\n";
        } else {
            echo "连接OK\n";
        }

        $s = socket_write($socket, $dataPack, strlen($dataPack));
        // var_dump($s);

        for ($i = 0; $i < 5; $i++) {
        // while (1) {

            socket_recv($socket, $buf, 4, MSG_PEEK);

            $len = unpack('Ilen', $buf);

            var_dump($len);

            $out = socket_read($socket, $len['len'] + 24);

            $out = unpack('Ilen/stoType/ItoIp/sfromType/IfromIp/IaskId/IaskId2/A*body', $out);

            print_r($out);

        }

        echo "关闭SOCKET...\n";
        socket_close($socket);
        echo "关闭OK\n";

        // $ip = '127.0.0.1';
        // $port = 20000;
        // $conn = $client -> connect($ip, $port);
        // if(!$conn)
        // {
        //     exit('服务器连接失败');
        // }

        // $status = $client -> send($dataPack);
        // if(!$status)
        // {
        //     exit('数据发送失败');
        // }

        // $ret = $client -> recv();

        // var_dump(substr($ret, 24));
    }
}
