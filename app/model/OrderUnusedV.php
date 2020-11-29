<?php


namespace app\model;


use app\lib\enum\CommonEnum;
use think\Facade\Db;
use think\Model;

class OrderUnusedV extends Model
{
    public function orders2($consumption_time)
    {
        $statistic = $this->where('ordering_date', '<', $consumption_time)
            ->select();
        return $statistic;
    }

    public static function orders($consumption_time)
    {
        $statistic = Db::name('canteen_order_t')
            ->alias('a')
            ->field('a.id,a.id as order_id,"one" as strategy_type,a.consumption_type,
            a.no_meal_money,a.no_meal_sub_money,a.money as parent_money,a.sub_money as parent_sub_money,
            a.money as order_money,a.sub_money as order_sub_money,a.ordering_date,b.name as dinner,
            a.c_id as canteen_id,a.company_id,a.staff_id')
            ->leftJoin('canteen_dinner_t b', 'a.d_id=b.id')
            ->where('a.ordering_date', '<', $consumption_time)
            ->where('a.pay', 'paid')
            ->where('a.used', CommonEnum::STATE_IS_FAIL)
            ->where('a.unused_handel', CommonEnum::STATE_IS_FAIL)
            ->where('a.state', CommonEnum::STATE_IS_OK)
            ->unionAll(function ($query) use ($consumption_time) {
                $query->table('canteen_order_sub_t')
                    ->alias('a')
                    ->field('a.id,a.order_id,"more" as strategy_type,a.consumption_type,a.no_meal_money,
                    a.no_meal_sub_money,b.money as parent_money,b.sub_money as parent_sub_money,
                    a.money as order_money,a.sub_money as order_sub_money,a.ordering_date,
                    c.name as dinner, b.canteen_id as canteen_id,b.company_id,b.staff_id')
                    ->leftJoin('canteen_order_parent_t b', 'a.order_id=b.id')
                    ->leftJoin('canteen_dinner_t c', 'b.dinner_id=c.id')
                    ->where('a.ordering_date', '<', $consumption_time)
                    ->where('b.pay', 'paid')
                    ->where('a.used', CommonEnum::STATE_IS_FAIL)
                    ->where('a.unused_handel', CommonEnum::STATE_IS_FAIL)
                    ->where('a.state', CommonEnum::STATE_IS_OK)
                    ->where('b.type', 2);
            })->select()->toArray();
        return $statistic;
    }

}