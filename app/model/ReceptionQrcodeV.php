<?php


namespace app\model;


use think\Model;

class ReceptionQrcodeV extends Model
{
    public function getUnused()
    {
        $list = self::where('status', 2)
            ->where('ordering_date', '<', date('Y-m-d'))
            ->select();
        return $list;
    }

}