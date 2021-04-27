<?php


namespace app\business;

use app\lib\enum\CommonEnum;
use app\lib\exception\SaveException;
use app\lib\exception\UpdateException;
use app\lib\Http;
use app\model\LogT;
use app\model\NextmonthPaySettingT;
use app\model\NextmonthPayT;
use app\model\OfficialTemplateT;
use app\model\OrderConsumptionV;
use app\lib\weixin\Template;
use app\model\TaskLogT;
use think\facade\Config;

class NextMonthPayBusiness
{
    public function handle()
    {
        //查询开启次月缴费功能的企业
        $isNextMonthPay = NextmonthPaySettingT::where('state', CommonEnum::STATE_IS_OK)->field('is_pay_day,c_id')->select();
        if (!empty($isNextMonthPay)) {
            $orderConsumptionDate = date("Y-m", strtotime("-1 month"));
            $nowDate = date('d');
            $orderConsumptionList = [];
            foreach ($isNextMonthPay as $k => $v) {
                $payBeginDate = explode('-', $v['is_pay_day']);
                if ($nowDate == $payBeginDate[0]) {
                    //查询已开启次月缴费企业的上一个月消费数据
                    $lastMonthOrderConsumption = (new OrderConsumptionV())->getOrderConsumption($v['c_id'], $orderConsumptionDate);
                    if (!empty($lastMonthOrderConsumption)) {
                        foreach ($lastMonthOrderConsumption as $k2 => $v2) {
                            array_push($orderConsumptionList, [
                                'dinner_id' => $v2['dinner_id'],
                                'dinner' => $v2['dinner'],
                                'canteen_id' => $v2['canteen_id'],
                                'canteen' => $v2['canteen'],
                                'company_id' => $v2['company_id'],
                                'consumption_date' => $v2['consumption_date'],
                                'department_id' => $v2['department_id'],
                                'department' => $v2['department'],
                                'username' => $v2['username'],
                                'phone' => $v2['phone'],
                                'status' => $v2['status'],
                                'order_money' => $v2['order_money'],
                                'order_count' => $v2['order_count'],
                                'staff_id' => $v2['staff_id'],
                                'pay_date' => date('Y-m', strtotime($v2['consumption_date']))
                            ]);
                        }
                        $save = (new NextmonthPayT())->saveAll($orderConsumptionList);
                        if (!$save) {
                            throw new SaveException(['msg' => "同步次月缴费数据失败"]);
                        }
                    }
                }
            }
        }
    }

    public function remind()
    {
        try {
            $companys = NextmonthPaySettingT::where('state', CommonEnum::STATE_IS_OK)
                ->where('remind_time', CommonEnum::STATE_IS_OK)
                ->field('group_concat(c_id) as c_ids')
                ->select()->toArray();
            if (!empty($companys)) {
                /* $template = OfficialTemplateT::where('type', 'payment')->find();
                 $type = $template->template_id;*/
                $c_ids = $companys[0]['c_ids'];
                //请求接口--begin
                $sendData = [
                    'type' => 'payment',
                    'id' => $c_ids
                ];
                $url = Config::get('setting.sendTemplateUrl');
                $res = Http::post($url, $sendData);
                $sendData['res'] = $res;
                TaskLogT::create(['content' => '发送缴费提醒消息：' . json_encode($sendData)]);
                //--end
            }
        } catch (\Exception $e) {
            TaskLogT::create(['content' => '发送缴费提醒消息失败：' . $e->getMessage()]);
        }

    }
}