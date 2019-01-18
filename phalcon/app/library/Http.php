<?php

namespace App\Library;

/**
 * HTTP
 */
class Http
{
    public static function get()
    {

    }

    public static function post($url, $param, $header = '', $cookie = '')
    {
        $ch = curl_init(); // 初始化
        curl_setopt($ch, CURLOPT_URL, $url); // 请求的 URL 地址
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 将 curl_exec() 获取的信息以文件流的形式返回，而非直接输出
        // curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_POST, true); // POST 请求
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param); // POST 参数
        if (stripos($url, 'https://') === 0) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 不验证证书
        }
        if ($header) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        if ($cookie) {
            curl_setopt($ch, CURLOPT_COOKIE, base64_decode($cookie)); // 带上 cookie 请求
        }
        $result = curl_exec($ch); // 执行

        if (!$result) {
            $result = [
                'curlErrno' => curl_errno($ch),
                'curlError' => curl_error($ch),
                'curlInfo' => curl_getinfo($ch),
            ];
        }

        curl_close($ch); // 关闭会话

        return $result; // 返回结果
    }
}
