<?php

namespace App\Controllers;

use Phalcon\Mvc\Controller;

class CompareController extends Controller
{
    // 模拟阈值数据
    private $thresholdData;

    // 模拟上报数据
    private $requestParam;

    // 初始化阈值配置
    private function initThresholdData()
    {
        $start1 = '1000'; // 开始时间 1
        $end1 = '1900'; // 结束时间 1
        $start2 = '0000'; // 开始时间 2
        $end2 = '0959'; // 结束时间 2

        $this->thresholdData = [
            [
                'id' => 1,
                'start' => $start1,
                'end' => $end1,
                'context' => [
                    ['name' => '测试A1', 'field' => 'A1', 'bind' => '>=', 'value' => '100'],
                    ['name' => '测试A2', 'field' => 'A2', 'bind' => '>', 'value' => '9%'],
                    ['name' => '测试A3', 'field' => 'A3', 'bind' => '==', 'value' => '1'],
                ],
            ],
            [
                'id' => 2,
                'start' => $start1,
                'end' => $end1,
                'context' => [
                    ['field' => 'B1', 'bind' => '>=', 'value' => '100'],
                ],
            ],
            [
                'id' => 3,
                'start' => $start2,
                'end' => $end2,
                'context' => [
                    ['field' => 'C1', 'bind' => '>=', 'value' => '100'],
                ],
            ],
        ];
    }

    // 初始化上报参数
    private function initRequestParam()
    {
        $this->requestParam = [
            'A1' => '100',
            'A2' => '9.1%',
            'A3' => '1',

            'B1' => '99',

            'C1' => '99',
        ];
    }

    private function checkThreshold()
    {
        $threshold = $this->thresholdData; // 阈值配置数据
        $request = $this->requestParam; // 阈值上报数据
        $param = []; // 处理后的阈值上报数据
        $hi = date('Hi'); // 当前时间，格式 hhmm，例如 0845 代表 AM 08:45，范围 0000 ~ 2359

        // 去掉不符合时间范围的规则
        foreach ($threshold as $k => $v) {
            // 处理符合时间的规则
            if ($hi >= $v['start'] && $hi <= $v['end']) {
                foreach ($v['context'] as $vv) {
                    // 如果配置为空，跳过
                    if (empty($vv['field'])) {
                        continue;
                    }

                    $_field = $vv['field'];
                    $_value = $request[$_field]; // 这里才开始接收客户端上报的数据（GET、POST 等）

                    // 格式验证，支持以下格式
                    // 12
                    // 12.34
                    // 12%
                    // 12.34%
                    $_tmp = $_value;
                    if (strpos($_tmp, '%')) {
                        $_tmp = str_replace('%', '', $_tmp);
                    }

                    // 验证不通过，报错
                    if (!is_numeric($_tmp)) {
                        return sprintf('阈值的值非数字或百分比（%s = %s）', $_field, $_value);
                    }

                    $param[$_field] = $_value; // 处理后的阈值上报数据
                }
            }

            // 去掉不符合时间的规则
            else {
                unset($threshold[$k]);
            }
        }

        // 如果当前时间没有报警阈值配置，则不报警
        if (empty($threshold)) {
            return '当前时段内没有符合的阈值';
        }

        $compareOr = []; // 用来比较 or 的关系（多条同时段阈值配置数据）
        foreach ($threshold as $v) {
            $compareAnd = []; // 用来比较 and 的关系（单条阈值配置数据）
            foreach ($v['context'] as $vv) {
                // 如果未配置，跳过
                if (empty($vv['field'])) {
                    continue;
                }
                // 如果阈值配置的参数和客户端上报的参数不符，报错
                if (!isset($param[$vv['field']])) {
                    return sprintf('阈值配置的数据需要和上报的阈值数据相对应（缺少 %s）', $vv['field']);
                }

                // 比较
                $_field = $vv['field'];
                $_value = $param[$_field];
                $_and = true; // 假设同一条阈值数据的多个配置符合要求
                switch ($vv['bind']) {
                    case '==':
                        $_and = $_value == $vv['value'];
                        break;
                    case '!=':
                        $_and = $_value != $vv['value'];
                        break;
                    case '>=':
                        $_and = $_value >= $vv['value'];
                        break;
                    case '<=':
                        $_and = $_value <= $vv['value'];
                        break;
                    case '>':
                        $_and = $_value > $vv['value'];
                        break;
                    case '<':
                        $_and = $_value < $vv['value'];
                        break;
                }

                // 记录 and 数据是 true 或 false
                // 多个 and 数据会被组合成数组（[true, true, false]）
                $compareAnd[] = (bool) $_and;
            }

            // 假设多个 and 全部为 true，则多条阈值数据中，or 也为 true
            $_or = true;

            // 若有 and 是 false，则多条阈值数据中，or 判断为 false
            if (empty($compareAnd) || in_array(false, $compareAnd)) {
                $_or = false;
            }

            // 记录 or 数据是 true 或 false
            $compareOr[] = (bool) $_or;
        }

        // 若 or 关系中（or 数组中），没有任何一个是 true，则验证不通过
        if (empty($compareOr) || !in_array(true, $compareOr)) {
            return '未通过验证';
        }

        // 否则验证通过
        return '通过验证';
    }

    public function indexAction()
    {
        // 初始化阈值配置模拟数据
        $this->initThresholdData();

        // 初始化上报参数
        $this->initRequestParam();

        // 验证阈值是否符合报警要求
        echo $this->checkThreshold();
    }
}
