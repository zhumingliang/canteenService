<?php
// 应用公共文件

/**
 * 日期加上指定天数
 * @param $count
 * @param $time_old
 * @return false|string
 */
function addDay($count, $time_old)
{
    $time_new = date('Y-m-d', strtotime('+' . $count . ' day',
        strtotime($time_old)));
    return $time_new;

}

/**
 * 日期减去指定天数
 * @param $count
 * @param $time_old
 * @return false|string
 */
function reduceDay($count, $time_old)
{
    $time_new = date('Y-m-d', strtotime('-' . $count . ' day',
        strtotime($time_old)));
    return $time_new;

}