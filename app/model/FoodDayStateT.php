<?php


namespace app\model;


use think\Model;

class FoodDayStateT extends Model
{
    public static function FoodStatus($canteen_id, $dinnerId, $day)
    {
        $list = self::where('canteen_id', $canteen_id)
            ->where('dinner_id', $dinnerId)
            ->where('day', '=', $day)
            ->select()->toArray();
        return $list;

    }

}