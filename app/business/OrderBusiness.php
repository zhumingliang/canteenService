<?php


namespace app\business;


use app\lib\enum\CommonEnum;
use app\lib\exception\UpdateException;
use app\model\OrderParentT;
use app\model\OrderSubT;
use app\model\OrderT;
use app\model\OrderUnusedV;
use function Composer\Autoload\includeFile;

class OrderBusiness
{
    public function handelUnusedOrder2()
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

    public function handelUnusedOrder()
    {
        $consumption_time = date('Y-m-d');
        //一次消费订单+非外卖多次扣费子订单
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
                if ($v['no_meal_sub_money'] > 0) {
                    (new AccountBusiness())->saveAccountRecords($v['ordering_date'], $v['canteen_id'], $v['no_meal_sub_money'], $v['strategy_type'],
                        $v['id'], $v['company_id'], $v['staff_id'], $v['dinner']);
                }
            }
        }


        //外卖多次扣费子订单
        $parentOrders = OrderParentT::unUsed($consumption_time);
        if (count($parentOrders)) {
            foreach ($parentOrders as $k => $v) {
                $subOrder = OrderSubT::where('order_id', $v['id'])
                    ->where('state', CommonEnum::STATE_IS_OK)
                    ->select();

                $subList = [];
                $allMoney = 0;
                foreach ($subOrder as $k2 => $v2) {
                    $allMoney += $v2['no_meal_sub_money'];
                    array_push($subList, [
                        'id' => $v['id'],
                        'consumption_type' => 'no_meals_ordered',
                        'money' => 0,
                        'delivery_fee' => 0,
                        'unused_handel' => CommonEnum::STATE_IS_OK,
                        'sub_money' => $v2['no_meal_sub_money']
                    ]);
                }
                $updateSub = (new OrderSubT())->saveAll($subList);
                if (!$updateSub) {
                    throw new UpdateException(['msg' => "更新子订单失败"]);
                }
                OrderParentT::update(['money' => $allMoney, 'delivery_fee' => 0, 'unused_handel' => CommonEnum::STATE_IS_OK],
                    ['id' => $v['id']]);
                if ($allMoney > 0) {
                    (new AccountBusiness())->saveAccountRecords($v['ordering_date'], $v['canteen_id'], $allMoney, 'more',
                        $v['id'], $v['company_id'], $v['staff_id'], $v['dinner']['name'], 1);

                }
            }

        }
    }


}