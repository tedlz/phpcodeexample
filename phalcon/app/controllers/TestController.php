<?php

namespace App\Controllers;

use Phalcon\Mvc\Controller;
use SplFileObject;

class TestController extends Controller
{
    public function indexAction()
    {
        $file = '/data/newplatform/other/task/app/data/FixTask/firepower_log_18.log';
        $start = 1;
        $incr = 11;
        while (true) {

            $end = $start + $incr - 1;
            $lines = $this->getFileLines($file, $start, $end);
            $start += $incr;
            // if (!$lines) {
            //     break;
            // }

            $sql = sprintf(
                "INSERT INTO `bullet_log_1801`(`uid`, `count`, `after`, `action`, `channel`, `orderId`, `terminal`, `dateline`) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')",
                str_replace(["\n", "'", ','], '', explode('=> ', $lines[5])[1]),
                str_replace(["\n", "'", ','], '', explode('=> ', $lines[4])[1]),
                str_replace(["\n", "'", ','], '', explode('=> ', $lines[6])[1]),
                str_replace(["\n", "'", ','], '', explode('=> ', $lines[2])[1]),
                str_replace(["\n", "'", ','], '', explode('=> ', $lines[3])[1]),
                str_replace(["\n", "'", ','], '', explode('=> ', $lines[7])[1]),
                str_replace(["\n", "'", ','], '', explode('=> ', $lines[9])[1]),
                str_replace(["\n", "'", ','], '', explode('=> ', $lines[8])[1])
            );

            echo $sql, PHP_EOL;

            // echo $start, PHP_EOL, $end;

            if ($start > 33) {
                break;
            }

        }
    }

    public function getFileLines($file, $startLine, $endLine, $method = 'rb')
    {
        $content = [];
        $count = $endLine - $startLine;
        $fp = new SplFileObject($file, $method);
        $fp->seek($startLine - 1); // 转到第N行, seek方法参数从0开始计数
        for ($i = 0; $i <= $count; ++$i) {
            $content[] = $fp->current(); // current()获取当前行内容
            $fp->next(); // 下一行
            if ($fp->eof()) {
                array_pop($content);
                break;
            }
        }
        return array_filter($content); // array_filter过滤：false,null,''
    }
}
