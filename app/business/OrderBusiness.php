<?php


namespace app\business;


use app\model\OrderT;
use app\model\OrderUnusedV;

class OrderBusiness
{
    public function handelUnusedOrder()
    {
        $consumption_time = date('Y-m-d');
        $dataList = [];
        $orders = OrderUnusedV::orders($consumption_time);
        if (count($orders)) {
            foreach ($orders as $k => $v) {
                if ($v['consumption_type'] == "no_meals_ordered") {
                    array_push($dataList, [
                        'id' => $v['id'],
                        'used' => 1
                    ]);
                } else {
                    if ($v['fixed'] == 1) {
                        array_push($dataList, [
                            'id' => $v['id'],
                            'used' => 1,
                            'consumption_type' => 'no_meals_ordered',
                            'money' => $v['no_meal_money'],
                            'sub_money' => $v['no_meal_sub_money']
                        ]);
                    } else {
                        array_push($dataList, [
                            'id' => $v['id'],
                            'used' => 1,
                            'consumption_type' => 'no_meals_ordered',
                            'sub_money' => $v['no_meal_sub_money']
                        ]);
                    }

                }

            }
            (new OrderT())->saveAll($dataList);
        }
    }

}