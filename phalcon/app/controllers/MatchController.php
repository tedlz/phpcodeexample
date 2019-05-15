<?php

namespace App\Controllers;

use Phalcon\Mvc\Controller;

class MatchController extends Controller
{
    public function indexAction()
    {
        $number = 16; // 赛事规模
        $groupNumber = 4; // 每组人数
        $group = ceil($number / $groupNumber);
        $data = [];
        // 小组数
        $start = 'A';
        for ($i = 0; $i < $group; $i++) {
            $name = sprintf('%s 组', $start++);
            $data[$name] = [];
        }
        // 每组轮数、每轮场数
        $m = $groupNumber; // 每组几人
        $r = 0; // 每组轮数
        $count = 0; // 每轮场数
        if ($m % 2 == 1) {
            // $m 为奇数时
            $r = max($m, ($m - 1) / 2); // 每组轮数
            $count = min($m, ($m - 1) / 2); // 每轮场数
        } else {
            // $m 为偶数时
            $r = max($m / 2, $m - 1); // 每组轮数
            $count = min($m / 2, $m - 1); // 每轮场数
        }
        foreach ($data as $k => $v) {
            for ($i = 1; $i <= $r; $i++) {
                $data[$k][] = sprintf('第 %d 轮', $i);
            }
        }

        $result = [];
        $sort = [];
        $index = 0;
        foreach ($data as $k => $v) {
            foreach ($v as $kk => $vv) {
                for ($i = 1; $i <= $count; $i++) {
                    // $x = ($index * $count) + ($kk * $group * $count) + $i;
                    $x = (($i - 1) * $group) + ($kk * $group * $count) + ($index + 1);
                    $vvv = sprintf('第 %d 场', $x);
                    $str = sprintf('%s_%s_%s', $k, $vv, $vvv);
                    // echo $str, PHP_EOL;
                    $sort[] = $x;
                    $result[] = [
                        'round' => $x,
                        'name' => $str,
                    ];
                }
            }
            $index++;
        }
        // die;
        array_multisort($sort, SORT_ASC, $result);
        foreach ($result as $v) {
            echo $v['name'], PHP_EOL;
        }
    }
}
