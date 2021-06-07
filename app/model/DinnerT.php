<?php


namespace app\model;


use think\Model;

class DinnerT extends Model
{
    public  static function dinner($dinnerId)
    {
        return self::where('id', $dinnerId)
            ->find();
    }

}