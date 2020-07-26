<?php


namespace app\model;


use app\lib\enum\CommonEnum;
use think\Model;

class ReceptionV extends Model
{
    public function getYestDayUnHandel()
    {

        $list = $this->where('status',  CommonEnum::STATE_IS_OK)
            ->select();
        return $list;

    }

}