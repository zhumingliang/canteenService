<?php


namespace app\business;


use app\lib\enum\CommonEnum;
use app\model\DinnerT;
use app\model\FoodMaterialT;
use app\model\MaterialOrderT;
use app\model\OrderDetailT;
use app\model\SubFoodT;

class Material
{
    public function orderMaterials()
    {
        $materials = MaterialOrderT::materials();
        if (!count($materials)) {
            return true;
        }

        $materialUpdateData = [];
        foreach ($materials as $k => $v) {
            $id = $v['id'];
            $dinnerId = $v['dinner_id'];
            $material = $v['material'];
            $canteenId = $v['canteen_id'];
            $day = $v['day'];
            $orderMaterialCount = 0;
            if (!$dinnerId || empty($material)) {
                array_push($materialUpdateData, [
                    'id' => $id,
                    'order_count' => $orderMaterialCount,
                    'status' => CommonEnum::STATE_IS_OK
                ]);
                continue;
            }
            //检测订餐是否截止
            if (!$this->checkDinner($dinnerId, $day)) {
                continue;
            }

            //获取含有材料的菜品信息
            $foods = FoodMaterialT::foods($canteenId, $material);
            if (count($foods)) {
                foreach ($foods as $k2 => $v2) {
                    $foodCount = $this->getFoodCount($v2['f_id'], $day);
                    $orderMaterialCount += $v2['count'] * $foodCount;
                }
            }
            array_push($materialUpdateData, [
                'id' => $id,
                'order_count' => $orderMaterialCount,
                'status' => CommonEnum::STATE_IS_OK
            ]);

        }
        (new MaterialOrderT())->saveAll($materialUpdateData);

    }


    private function checkDinner($dinnerId, $ordering_date)
    {
        $dinner = DinnerT::dinner($dinnerId);
        if (!$dinner) {
            return true;
        }
        $type = $dinner->type;
        if ($type == 'week') {
            return true;
        }
        $limit_time = $dinner->limit_time;
        $type_number = $dinner->type_number;
        $limit_time = $ordering_date . ' ' . $limit_time;
        $expiryDate = $this->prefixExpiryDate($limit_time, [$type => $type_number], '-');
        if (time() > strtotime($expiryDate)) {
            return true;
        }
        return false;
    }

    public
    function prefixExpiryDate($expiry_date, $params, $symbol = '+')
    {
        $type = ['minute', 'hour', 'day', 'week', 'month', 'year'];
        $exit = 0;
        foreach ($type as $k => $v) {
            if (key_exists($v, $params)) {
                $exit = 1;

                if ($params[$v] > 0) {
                    $expiry_date = date('Y-m-d H:i:s', strtotime($symbol . $params[$v] . "$v", strtotime($expiry_date)));
                }
                break;
            }

        }
        if (!$exit) {
            $expiry_date = date('Y-m-d H:i:s', strtotime($symbol . config("setting.qrcode_expire_in") . "minute", strtotime($expiry_date)));

        }
        return $expiry_date;
    }


    private
    function getFoodCount($foodId, $day)
    {
        $foodCount = OrderDetailT::foodCount($day, $foodId) + SubFoodT::foodCount($day, $foodId);
        return $foodCount;

    }

}