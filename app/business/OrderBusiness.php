<?php


namespace app\business;


use app\lib\enum\CommonEnum;
use app\lib\exception\UpdateException;
use app\model\CompanyStaffT;
use app\model\OrderParentT;
use app\model\OrderSubT;
use app\model\OrderT;
use app\model\OrderUnusedV;
use app\model\PunishmentStrategyT;
use app\model\StaffPunishmentT;
use function Composer\Autoload\includeFile;

class OrderBusiness
{
    /* public function handelUnusedOrder2()
    {
        $consumption_time = date('Y-m-d');
        $orders = (new  OrderUnusedV())->orders($consumption_time);
        $parentMoneyArr = [];
        $parentCancelArr = [];
        if (count($orders)) {
            $orders = $this->sortOrders($orders);
            foreach ($orders as $k => $v) {
                $staffStatus = $v['status'];
                if ($staffStatus == 4) {
                    //用户已经在黑名单-取消订单
                    if ($v['strategy_type'] == 'one') {
                        OrderT::update(['state', CommonEnum::STATE_IS_FAIL],
                            ['id' => $v['id']]);

                    } else {
                        OrderSubT::update([
                            'state', CommonEnum::STATE_IS_FAIL],
                            ['id' => $v['id']
                            ]);
                        if (!in_array($v['order_id'], $parentCancelArr)) {
                            OrderParentT::update([
                                'id' => $v['order_id'],
                                'state' => CommonEnum::STATE_IS_FAIL
                            ]);
                            array_push($parentCancelArr, $v['order_id']);
                        }
                    }

                } else {
                    //检测是否违规

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
    }*/

    public function handelUnusedOrder()
    {
        $consumption_time = date('Y-m-d');
        //一次消费订单+非外卖多次扣费子订单
        $orders = (new  OrderUnusedV())->orders($consumption_time);
        $parentMoneyArr = [];
        $parentCancelArr = [];
        $parentPunishmentArr = [];
        if (count($orders)) {
            foreach ($orders as $k => $v) {
                $staffStatus = $v['status'];
                if ($staffStatus == 4) {
                    //用户已经在黑名单-取消订单
                    if ($v['strategy_type'] == 'one') {
                        OrderT::update(
                            [
                                'state', CommonEnum::STATE_IS_FAIL
                            ],
                            [
                                'id' => $v['id']
                            ]
                        );

                    } else {
                        OrderSubT::update([
                            'state', CommonEnum::STATE_IS_FAIL],
                            [
                                'id' => $v['id']
                            ]
                        );
                        if (!in_array($v['order_id'], $parentCancelArr)) {
                            OrderParentT::update([
                                'id' => $v['order_id'],
                                'state' => CommonEnum::STATE_IS_FAIL
                            ]);
                            array_push($parentCancelArr, $v['order_id']);
                        }
                    }

                } else {
                    if ($v['strategy_type'] == 'one') {
                        // 检测用户惩罚策略
                        if ($staffStatus == 3) {
                            //白名单
                            $violationCount = 0;
                        } else {
                            $violationCount = $this->checkPunishment($v['staff_id'], $staffStatus, $v['canteen_id'], $v['staff_type_id']);

                        }
                        OrderT::update(
                            [
                                'consumption_type' => 'no_meals_ordered',
                                'money' => 0,
                                'unused_handel' => CommonEnum::STATE_IS_OK,
                                'sub_money' => $v['no_meal_sub_money'],
                                'violation_count' => $violationCount
                            ],
                            [
                                'id' => $v['id']
                            ]
                        );

                    } else {
                        OrderSubT::update(
                            [
                                'consumption_type' => 'no_meals_ordered',
                                'money' => 0,
                                'unused_handel' => CommonEnum::STATE_IS_OK,
                                'sub_money' => $v['no_meal_sub_money']
                            ],
                            [
                                'id' => $v['id']
                            ]
                        );
                        if (key_exists($v['order_id'], $parentMoneyArr)) {
                            $parentMoney = $parentMoneyArr[$v['order_id']];
                        } else {
                            $parentMoney = $v['parent_money'];
                        }
                        $newParentMoney = $parentMoney - $v['order_money'] - $v['order_sub_money'] + $v['no_meal_sub_money'];

                        if (in_array($v['order_id'], $parentPunishmentArr)) {
                            $violationCount = 0;
                        } else {
                            if ($staffStatus == 3) {
                                //白名单
                                $violationCount = 0;
                            } else {
                                $violationCount = $this->checkPunishment($v['staff_id'], $staffStatus, $v['canteen_id'], $v['staff_type_id']);
                            }
                            array_push($parentPunishmentArr, $v['order_id']);
                        }
                        OrderParentT::update(
                            [
                                'money' => $newParentMoney,
                                'violation_count' => $violationCount
                            ],
                            [
                                'id' => $v['order_id']
                            ]
                        );
                        $parentMoneyArr[$v['order_id']] = $newParentMoney;
                    }
                    if ($v['no_meal_sub_money'] > 0) {
                        (new AccountBusiness())->saveAccountRecords($v['ordering_date'], $v['canteen_id'], $v['no_meal_sub_money'], $v['strategy_type'],
                            $v['id'], $v['company_id'], $v['staff_id'], $v['dinner']);
                    }
                }
            }
        }

        //外卖多次扣费子订单
        $parentOrders = OrderParentT::unUsedOutsiderOrder($consumption_time);
        if (count($parentOrders)) {
            $parentOrders = $this->sortOrders2($parentOrders);
            foreach ($parentOrders as $k => $v) {
                $staffStatus = $v['staff']['status'];
                if ($staffStatus == 4) {
                    //用户已经在黑名单-取消订单
                    OrderParentT::update([
                        'id' => $v['id'],
                        'state' => CommonEnum::STATE_IS_FAIL
                    ]);
                    OrderSubT::update([
                        'order_id' => $v['id'],
                        'state' => CommonEnum::STATE_IS_FAIL
                    ]);
                } else {
                    // 检测用户惩罚策略
                    if ($staffStatus == 3) {
                        //白名单
                        $violationCount = 0;
                    } else {
                        $violationCount = $this->checkPunishment($v['staff_id'], $staffStatus, $v['canteen_id'], $v['staff_type_id']);
                    }
                    $subOrder = OrderSubT::where('order_id', $v['id'])
                        ->where('state', CommonEnum::STATE_IS_OK)
                        ->select();
                    $subList = [];
                    $allMoney = 0;
                    foreach ($subOrder as $k2 => $v2) {
                        $allMoney += $v2['no_meal_sub_money'];
                        array_push($subList, [
                            'id' => $v2['id'],
                            'consumption_type' => 'no_meals_ordered',
                            'money' => 0,
                            'unused_handel' => CommonEnum::STATE_IS_OK,
                            'sub_money' => $v2['no_meal_sub_money']
                        ]);
                    }
                    $updateSub = (new OrderSubT())->saveAll($subList);
                    if (!$updateSub) {
                        throw new UpdateException(['msg' => "更新子订单失败"]);
                    }
                    OrderParentT::update(
                        [
                            'money' => $allMoney,
                            'delivery_fee' => 0,
                            'unused_handel' => CommonEnum::STATE_IS_OK,
                            'violation_count' => $violationCount
                        ],
                        [
                            'id' => $v['id']
                        ]
                    );
                    if ($allMoney > 0) {
                        (new AccountBusiness())->saveAccountRecords($v['ordering_date'], $v['canteen_id'], $allMoney, 'more',
                            $v['id'], $v['company_id'], $v['staff_id'], $v['dinner']['name'], 1);

                    }

                }
            }

        }
    }


    public function sortOrders($statistic)
    {
        $dinnerSort = ['早餐', '午餐', '下午餐', '晚餐', '夜宵'];
        $sortList = [];
        foreach ($dinnerSort as $k => $v) {
            foreach ($statistic as $k2 => $v2) {
                if ($v == $v2['dinner']) {
                    array_push($sortList, $statistic[$k2]);
                    unset($statistic[$k2]);
                }
            }

        }
        $allList = array_merge($sortList, $statistic);
        return $allList;

    }

    public function sortOrders2($statistic)
    {
        $dinnerSort = ['早餐', '午餐', '下午餐', '晚餐', '夜宵'];
        $sortList = [];
        foreach ($dinnerSort as $k => $v) {
            foreach ($statistic as $k2 => $v2) {
                if ($v == $v2['dinner']['name']) {
                    array_push($sortList, $statistic[$k2]);
                    unset($statistic[$k2]);
                }
            }

        }

        $allList = array_merge($sortList, $statistic);
        return $allList;

    }


    public function checkPunishment($staffId, $staffStatus, $canteenId, $staffTypeId)
    {
        //检查有没有设置惩罚策略
        $punishment = PunishmentStrategyT::punishment($canteenId, $staffTypeId);
        $staffNoMealCount = 0;
        if ($punishment && !empty($punishment['detail']['count'])) {
            $punishmentNoMealCount = $punishment['detail']['count'];
            //设置了惩罚策略 处理订单
            //1.检测是否违规上限
            $staffNoMeal = StaffPunishmentT::noMealPunishment($staffId);
            if (!$staffNoMeal) {
                StaffPunishmentT::create([
                    'staff_id' => $staffId,
                    'no_meal' => 0,
                    'no_booking' => 0,
                ]);
                $staffNoMealCount = 1;
            } else {
                $staffNoMealCount = $staffNoMeal['no_meal'] + 1;
            }

            if ($staffNoMealCount >= $punishmentNoMealCount) {
                //违规次数达到上限
                //用户进入黑名单
                CompanyStaffT::update([
                    'id' => $staffId,
                    'status' => 4
                ]);
            } else {
                if ($staffStatus == 1) {
                    CompanyStaffT::update([
                        'id' => $staffId,
                        'status' => 2
                    ]);
                }

            }
            //更新用户违规次数
            StaffPunishmentT::update([
                'no_meal' => $staffNoMealCount
            ], [
                'staff_id' => $staffId
            ]);

        }

        return $staffNoMealCount;
    }


}