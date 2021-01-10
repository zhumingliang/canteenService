<?php


namespace app\business;


use app\lib\enum\CommonEnum;
use app\lib\enum\FoodEnum;
use app\model\AutomaticT;
use app\model\FoodDayStateT;
use app\model\TaskLogT;

class FoodBusiness
{
    public function autoUpFoods()
    {
        try {
            //查询出今日需要处理的自动上架
            $w = date('w');
            $auto = AutomaticT::auto($w);
            if (count($auto)) {
                foreach ($auto as $k => $v) {
                    $repeatWeek = $v['repeat_week'];
                    $repeatDay = $this->getRepeatDay($repeatWeek);
                    $this->upAll($v, $repeatDay);
                }
            }
            TaskLogT::create(['content' => '自动上架成功']);
        } catch (\Exception $e) {
            TaskLogT::create(['content' => '自动上架失败：' . $e->getMessage()]);


        }


    }

    public function upAll($auto, $day)
    {
        $canteenId = $auto['canteen_id'];
        $dinnerId = $auto['dinner_id'];
        $foodDay = FoodDayStateT::FoodStatus($canteenId, $dinnerId, $day);
        $foodList = [];
        if (!count($auto)) {
            $autoFoods = $auto['foods'];
            foreach ($autoFoods as $k => $v) {
                if (!count($foodDay)) {
                    array_push($foodList, [
                        'f_id' => $v['food_id'],
                        'status' => FoodEnum::STATUS_UP,
                        'day' => $day,
                        'user_id' => 0,
                        'canteen_id' => $canteenId,
                        'default' => CommonEnum::STATE_IS_FAIL,
                        'dinner_id' => $dinnerId
                    ]);
                } else {
                    $exit = false;
                    foreach ($foodDay as $k2 => $v2) {
                        if ($v['food_id'] == $v2['f_id']) {
                            $exit = true;
                            unset($foodDay[$k2]);
                            break;
                        }
                    }
                    if (!$exit) {
                        array_push($foodList, [
                            'f_id' => $v['food_id'],
                            'status' => FoodEnum::STATUS_UP,
                            'day' => $day,
                            'user_id' => 0,
                            'canteen_id' => $canteenId,
                            'default' => CommonEnum::STATE_IS_FAIL,
                            'dinner_id' => $dinnerId
                        ]);
                    }
                }
            }

        }

        if (count($foodList)) {
            $save = (new FoodDayStateT())->saveAll($foodList);
            if (!$save) {
                throw new SaveException(['msg' => '上架失败']);
            }
        }

    }


    private function getRepeatDay($repeatWeek)
    {
        $w = date('w');
        $repeatWeek = $repeatWeek == 0 ? 7 : $repeatWeek;
        if ($w == 0) {
            //今日这周日
            return date('Y-m-d', strtotime('+' . $repeatWeek . ' day', time()));

        } else {
            if ($w <= $repeatWeek) {
                return date('Y-m-d', strtotime('+' . ($repeatWeek - $w) . ' day', time()));
            } else {
                return date('Y-m-d', strtotime('+' . (7 - abs($w - $repeatWeek)) . ' day', time()));

            }
        }


    }
}