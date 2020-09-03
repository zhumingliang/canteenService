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
        $oneDataList = [];
        $moreDataList = [];
        $parentDataList = [];
        $orders = (new  OrderUnusedV())->orders($consumption_time);
        if (count($orders)) {
            foreach ($orders as $k => $v) {
                if ($v['strategy_type'] == 'one') {
                    array_push($oneDataList, [
                        'id' => $v['id'],
                        'consumption_type' => 'no_meals_ordered',
                        'money' => 0,
                        'unused_handel' => CommonEnum::STATE_IS_OK,
                        'sub_money' => $v['no_meal_sub_money']
                    ]);
                } else {
                    array_push($moreDataList, [
                        'id' => $v['id'],
                        'consumption_type' => 'no_meals_ordered',
                        'money' => 0,
                        'unused_handel' => CommonEnum::STATE_IS_OK,
                        'sub_money' => $v['no_meal_sub_money']
                    ]);

                    array_push($parentDataList, [
                        'id' => $v['order_id'],
                        'money' => $v['parent_money'] - $v['order_money'] - $v['order_sub_money'] + $v['no_meal_sub_money'],
                    ]);

                }

            }
            if (count($oneDataList)) {
                (new OrderT())->saveAll($oneDataList);
            }
            if (count($moreDataList)) {
                (new OrderSubT())->saveAll($oneDataList);
            }
            if (count($parentDataList)) {
                (new OrderParentT())->saveAll($oneDataList);
            }
        }
    }


}