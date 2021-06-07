<?php


namespace app\model;


use app\lib\enum\CommonEnum;
use think\Model;

class MaterialOrderT extends Model
{
    public static function materials()
    {
        return self::where('status',CommonEnum::STATE_IS_FAIL)
            ->where('state',CommonEnum::STATE_IS_OK)
            ->select();
    }

}