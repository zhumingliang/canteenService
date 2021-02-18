<?php


namespace app\business;


use app\lib\enum\CommonEnum;
use app\lib\enum\FoodEnum;
use app\lib\exception\ParameterException;
use app\lib\exception\SaveException;
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
        $alreadyFoods = [];
        $cancelFoods = [];
        if (count($foodDay)) {
            foreach ($foodDay as $k => $v) {
                if (in_array([$v['f_id']], $alreadyFoods)||in_array([$v['f_id']], $cancelFoods)) {
                    continue;
                }
                if ($v['status'] != FoodEnum::STATUS_DOWN) {
                    array_push($foodList, [
                        'id' => $v['id'],
                        'status' => FoodEnum::STATUS_UP
                    ]);
                    array_push($alreadyFoods, $v['f_id']);

                }else{
                    array_push($cancelFoods, $v['f_id']);

                }
                array_push($alreadyFoods, $v['f_id']);
            }
        }

        if ($auto) {
            if (!count($auto['foods'])) {
                throw new ParameterException(['msg' => "自动上架菜品未设置"]);
            }
            $autoFoods = $auto['foods'];
            foreach ($autoFoods as $k => $v) {
                if (in_array([$v['food_id']], $alreadyFoods)||in_array([$v['food_id']], $cancelFoods)) {
                    continue;
                }
                array_push($foodList, [
                    'f_id' => $v['food_id'],
                    'status' => FoodEnum::STATUS_UP,
                    'day' => $day,
                    'user_id' => 0,
                    'canteen_id' => $canteenId,
                    'default' => CommonEnum::STATE_IS_FAIL,
                    'dinner_id' => $dinnerId
                ]);
                array_push($alreadyFoods, $v['food_id']);

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
        $w = date('w') == 0 ? 7 : date('w');
        $repeatWeek = $repeatWeek == 0 ? 7 : $repeatWeek;
        return addDay(7 + ($repeatWeek - $w), \date('Y-m-d'));

    }}