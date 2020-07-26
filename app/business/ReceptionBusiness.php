<?php


namespace app\business;


use app\lib\Date;
use app\lib\enum\CommonEnum;
use app\model\ReceptionT;
use app\model\ReceptionV;

class ReceptionBusiness
{
    /**
     * 处理过期申请
     * @throws \Exception
     */
    public function handelReception()
    {
        //$day = Date::reduceDay(1, date('Y-m-d'));
        $receptions = ReceptionV::getYestDayUnHandel();
        $dataList = [];
        if (count($receptions)) {
            foreach ($receptions as $k => $v) {
                if ($this->checkOrderCanHandel($v['type'], $v['limit_time'], $v['type_number'], $v['ordering_date'])) {
                    array_push($dataList, [
                        'id' => $v['id'],
                        'status' => 3
                    ]);
                }
            }

        }

        if (count($dataList)) {
            (new ReceptionT())->saveAll($dataList);
        }
    }

    private
    function checkOrderCanHandel($type, $limit_time, $type_number, $ordering_date)
    {
        //获取餐次设置
        if ($type == 'day') {
            $expiryDate = $this->prefixExpiryDateForOrder($ordering_date, $type_number, '-');
            if (time() > strtotime($expiryDate . ' ' . $limit_time)) {
                return true;

            }
        } else if ($type == 'week') {
            $ordering_date_week = date('W', strtotime($ordering_date));
            $now_week = date('W', time());
            if ($ordering_date_week <= $now_week) {
                return true;
            }
            if (($ordering_date_week - $now_week) === 1) {
                if ($type_number == 0) {
                    //星期天
                    if (strtotime($limit_time) < time()) {
                        return true;
                    }
                } else {
                    //周一到周六
                    if (date('w', time()) > $type_number) {
                        return true;
                    } else if (date('w', time()) == $type_number && strtotime($limit_time) < time()) {
                        return true;
                    }
                }
            }

        }
        return false;
    }


    private function prefixExpiryDateForOrder($expiry_date, $count, $symbol = '+')
    {
        $expiry_date = date('Y-m-d', strtotime($symbol . $count . "day", strtotime($expiry_date)));
        return $expiry_date;
    }

}