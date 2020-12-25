<?php


namespace app\model;


use app\lib\enum\CommonEnum;
use think\Model;

class MachineT extends Model
{
    public static function check()
    {
        return self::where('remind', CommonEnum::STATE_IS_OK)
            ->where('state', CommonEnum::STATE_IS_OK)
            ->select()->toArray();
    }

}