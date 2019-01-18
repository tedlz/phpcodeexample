<?php

namespace App\Library;

/**
 * 依据给定的日期计算相关的日期或日期范围
 */
class Date
{
    private static $_format = 'Y-m-d';

    // 获取今天的日期
    public static function getToday($timestamp = '', $format = '')
    {
        if (empty($format)) {
            $format = self::$_format;
        }
        if ($timestamp === '') {
            $timestamp = time();
        }
        return date($format, $timestamp);
    }

    // 获取昨天的日期
    public static function getYesterday($timestamp = '', $format = '')
    {
        if (empty($format)) {
            $format = self::$_format;
        }
        if ($timestamp === '') {
            $timestamp = time();
        }
        return date($format, $timestamp - 86400);
    }

    // 获取星期（1-7）
    public static function getWeek($timestamp = '')
    {
        if ($timestamp === '') {
            $timestamp = time();
        }
        return date('N', $timestamp);
    }

    // 获取周一
    public static function getWeekMonday($timestamp = '', $format = '')
    {
        if (empty($format)) {
            $format = self::$_format;
        }
        if ($timestamp === '') {
            $timestamp = time();
        }
        return date($format, $timestamp - (self::getWeek($timestamp) - 1) * 86400);
    }

    // 获取周日
    public static function getWeekSunday($timestamp = '', $format = '')
    {
        if (empty($format)) {
            $format = self::$_format;
        }
        if ($timestamp === '') {
            $timestamp = time();
        }
        return date($format, $timestamp + (7 - self::getWeek($timestamp)) * 86400);
    }

    // 获取上周一
    public static function getLastWeekMonday($timestamp = '', $format = '')
    {
        if (empty($format)) {
            $format = self::$_format;
        }
        if ($timestamp === '') {
            $timestamp = time();
        }
        return date($format, strtotime(self::getWeekMonday($timestamp)) - 604800);
    }

    // 获取上周日
    public static function getLastWeekSunday($timestamp = '', $format = '')
    {
        if (empty($format)) {
            $format = self::$_format;
        }
        if ($timestamp === '') {
            $timestamp = time();
        }
        return date($format, strtotime(self::getWeekSunday($timestamp)) - 604800);
    }

    // 获取月首日
    public static function getMonthFirstDay($timestamp = '', $format = '')
    {
        if (empty($format)) {
            $format = 'Y-m-01';
        }
        if ($timestamp === '') {
            $timestamp = time();
        }
        return date($format, $timestamp);
    }

    // 获取月尾日
    public static function getMonthLastDay($timestamp = '', $format = '')
    {
        if (empty($format)) {
            $format = 'Y-m-t';
        }
        if ($timestamp === '') {
            $timestamp = time();
        }
        return date($format, $timestamp);
    }

    // 获取上月首日
    public static function getLastMonthFirstDay($timestamp = '', $format = '')
    {
        if (empty($format)) {
            $format = 'Y-m-01';
        }
        if ($timestamp === '') {
            $timestamp = time();
        }
        return date($format, mktime(0, 0, 0, date('m', $timestamp) - 1, 1, date('Y')));
    }

    // 获取上月尾日
    public static function getLastMonthLastDay($timestamp = '', $format = '')
    {
        if (empty($format)) {
            $format = 'Y-m-t';
        }
        if ($timestamp === '') {
            $timestamp = time();
        }
        return date($format, mktime(0, 0, 0, date('m', $timestamp) - 1, 1, date('Y')));
    }
}
