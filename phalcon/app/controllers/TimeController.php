<?php

namespace App\Controllers;

use Phalcon\Mvc\Controller;

class TimeController extends Controller
{
    private $_date;
    private $_time; // 当前时间戳
    private $_N; // 周几（1-7）
    private $_today; // 今日，格式 Y-m-d
    private $_yesterday; // 昨日，格式 Y-m-d
    private $_weekStart; // 本周开始日期
    private $_weekEnd; // 本周结束日期
    private $_lastWeekStart; // 上周开始日期
    private $_lastWeekEnd; // 上周结束日期
    private $_monthStart; // 本月开始日期
    private $_monthEnd; // 本月结束日期
    private $_lastMonthStart; // 上月开始日期
    private $_lastMonthEnd; // 上月结束日期

    public function indexAction()
    {
        $this->_date = $this->request->get('date', null, date('Y-m-d'));
        $this->_time = strtotime($this->_date); // 以下所有时间都依赖于此，方便测试
        // $this->_time = time();
        $this->_N = date('N', $this->_time);
        $this->_today = date('Y-m-d', $this->_time);
        $this->_yesterday = date('Y-m-d', $this->_time - 86400);
        $this->_weekStart = date('Y-m-d', $this->_time - ($this->_N - 1) * 86400); // 86400 = 24 * 3600
        $this->_weekEnd = date('Y-m-d', $this->_time + (7 - $this->_N) * 86400);
        $this->_lastWeekStart = date('Y-m-d', strtotime($this->_weekStart) - 604800); // 604800 = 24 * 3600 * 7
        $this->_lastWeekEnd = date('Y-m-d', strtotime($this->_weekEnd) - 604800);
        $this->_monthStart = date('Y-m-01', $this->_time);
        $this->_monthEnd = date('Y-m-t', $this->_time);
        $this->_lastMonthStart = date('Y-m-01', mktime(0, 0, 0, date('m', $this->_time) - 1, 1, date('Y')));
        $this->_lastMonthEnd = date('Y-m-t', mktime(0, 0, 0, date('m', $this->_time) - 1, 1, date('Y')));

        print_r([
            'time' => $this->_time,
            'N' => $this->_N,
            'today' => $this->_today,
            'yesterday' => $this->_yesterday,
            'weekStart' => $this->_weekStart,
            'weekEnd' => $this->_weekEnd,
            'lastWeekStart' => $this->_lastWeekStart,
            'lastWeekEnd' => $this->_lastWeekEnd,
            'monthStart' => $this->_monthStart,
            'monthEnd' => $this->_monthEnd,
            'lastMonthStart' => $this->_lastMonthStart,
            'lastMonthEnd' => $this->_lastMonthEnd,
        ]);
    }

    public function transferSeconds()
    {
        $seconds = 86200;
        $arr = [
            'hours' => floor($seconds / 3600),
            'minutes' => floor($seconds % 3600 / 60),
            'seconds' => $seconds % 60,
        ];
        $arr = array_merge($arr, [
            'show' => sprintf('%02d:%02d\'%02d"', $arr['hours'], $arr['minutes'], $arr['seconds']),
        ]);
    }

    public function test()
    {
        $week = ['第一周', '第二周', '第三周', '第四周', '第五周', '第六周'];
        $start = '2017-01-01';
        $end = '2017-12-31';
        $index = 0;
        for ($i = strtotime($start); $i <= strtotime($end); $i += 86400) {
            $date = date('Y-m-d', $i);
            $m = substr($date, 5, 2);
            $d = substr($date, 8, 2);
            $w = date('w', $i);
            if ($d != 1 && $w == 1) {
                $index++;
            }
            if ($m != 1 && $d == 1) {
                $index = 0;
            }
            $how = $week[$index];

            echo sprintf('[日期]%s, [月份]%s, [星期]%s, [几周]%s', $date, $m, $w == 0 ? 7 : $w, $how), PHP_EOL;
        }
    }
}
