<?php


namespace app\model;

use app\lib\enum\CommonEnum;
use think\facade\Db;
use think\Model;

class OrderConsumptionV extends Model
{
    public function getOrderConsumption($c_id, $consumption_date)
    {
        $dateArr = explode('-',$consumption_date);
        $list = self::where('company_id', $c_id)
            ->where('year(consumption_date) ='. $dateArr[0])
            ->where('month(consumption_date) ='.$dateArr[1])
            ->where('type',1)
            ->select()
            ->toArray();
        return $list;
    }
}