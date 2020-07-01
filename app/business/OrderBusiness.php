<?php


namespace app\business;


use app\lib\enum\CommonEnum;
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
                array_push($dataList, [
                    'id' => $v['id'],
                    'consumption_type' => 'no_meals_ordered',
                    'money' => 0,
                    'unused_handel' => CommonEnum::STATE_IS_OK,
                    'sub_money' => $v['no_meal_sub_money']
                ]);
            }
            (new OrderT())->saveAll($dataList);
        }
    }


}