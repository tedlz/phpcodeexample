<?php

namespace App\Controllers;

use Phalcon\Mvc\Controller;

class OtherController extends Controller
{
    public function getPublishInfoAction()
    {
        $time = floor(time() / 300) * 300;
        $startTime = $time - 300;
        $endTime = $time;
        $key = 'vZa9N3IM';
        $t = time();
        $domainArr = [
            'alrtmpup.other.cdn.zhanqi.tv',
            'alrtmpup.cdn.zhanqi.tv',
        ];

        $k = md5(strtolower($key . '/liveapi/external/zhanqi/get_publish_infozhanqi' . dechex($t)));
        $domain = 'alrtmpup.other.cdn.zhanqi.tv';
        $url = 'http://liveapi.alivecdn.com/liveapi/external/zhanqi/get_publish_info?domain=' . $domain . '&startTime=' . $startTime . '&endTime=' . $endTime . '&k=' . $k . '&t=' . dechex($t) . '&from=zhanqi';
        echo $url;
    }

    public function testAction()
    {
        $time = floor(time() / 300) * 300;
        $startTime = $time - 300;
        $endTime = $time;
        $key = 'vZa9N3IM';
        $t = time();
        $domainArr = [
            'alrtmpup.other.cdn.zhanqi.tv',
            'alrtmpup.cdn.zhanqi.tv',
        ];

        $k = md5(strtolower($key . '/liveapi/external/zhanqi/get_publish_infozhanqi' . dechex($t)));

        foreach ($domainArr as $k => $domain) {
            $url = 'http://liveapi.alivecdn.com/liveapi/external/zhanqi/get_publish_info?domain=' . $domain . '&startTime=' . $startTime . '&endTime=' . $endTime . '&k=' . $k . '&t=' . dechex($t) . '&from=zhanqi';
            echo $url, PHP_EOL;
        }
    }
}
