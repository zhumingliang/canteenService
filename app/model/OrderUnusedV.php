<?php


namespace app\model;


use think\Model;

class OrderUnusedV extends Model
{
    public static function orders($consumption_time)
    {

        $statistic = self::where('ordering_date', '<', $consumption_time)
            ->select();
        return $statistic;
    }
}