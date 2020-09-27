<?php


namespace app\business;


use app\lib\enum\CommonEnum;
use app\model\OrderParentT;
use app\model\OrderSubT;
use app\model\OrderT;
use app\model\OrderUnusedV;

class OrderBusiness
{
    public function handelUnusedOrder()
    {
        $consumption_time = date('Y-m-d');
        $orders = (new  OrderUnusedV())->orders($consumption_time);
        $parentMoneyArr = [];
        if (count($orders)) {
            foreach ($orders as $k => $v) {
                if ($v['strategy_type'] == 'one') {
                    OrderT::update(['consumption_type' => 'no_meals_ordered',
                        'money' => 0,
                        'unused_handel' => CommonEnum::STATE_IS_OK,
                        'sub_money' => $v['no_meal_sub_money']], ['id' => $v['id']]);

                } else {
                    OrderSubT::update([
                        'consumption_type' => 'no_meals_ordered',
                        'money' => 0,
                        'unused_handel' => CommonEnum::STATE_IS_OK,
                        'sub_money' => $v['no_meal_sub_money']
                    ], ['id' => $v['id']]);

                    if (key_exists($v['order_id'], $parentMoneyArr)) {
                        $parentMoney = $parentMoneyArr[$v['order_id']];
                    } else {
                        $parentMoney = $v['parent_money'];
                    }
                    $newParentMoney = $parentMoney - $v['order_money'] - $v['order_sub_money'] + $v['no_meal_sub_money'];
                    OrderParentT::update(['money' => $newParentMoney
                    ], ['id' => $v['order_id']]);

                    $parentMoneyArr[$v['order_id']] = $newParentMoney;

                }

            }
        }
    }


}