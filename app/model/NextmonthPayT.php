<?php


namespace app\model;


use think\Model;

class NextmonthPayT extends Model
{
    public function getNoPayStaffs($c_id, $orderConsumptionDate)
    {
        $list = self::where('company_id', $c_id)
            ->where('state', 2)
            ->where('pay_date', $orderConsumptionDate)
            ->field('staff_id,phone,username,sum(order_money) as pay_money,pay_date')
            ->group('staff_id')
            ->select()->toArray();
        return $list;
    }
}