<?php


namespace app\model;


use think\Model;

class StaffPunishmentT extends Model
{
    public static function noMealPunishment($staffId)
    {
        $punishment = self::where('staff_id', $staffId)
            ->field('no_meal')
            ->find();
        return $punishment;

    }

}