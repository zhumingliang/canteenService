<?php


namespace app\business;


use app\model\AccountRecordsT;
use app\model\CompanyAccountT;
use app\lib\enum\CommonEnum;
use app\lib\exception\SaveException;
use app\model\CompanyT;
use app\model\LogT;
use think\Db;

class AccountBusiness
{
    public function saveAccountRecords($consumptionDate, $canteenId, $money, $type, $orderId, $companyId, $staffId, $typeName, $outsider = 2)
    {
        $company = CompanyT::company($companyId);
        if ($company->account_status == CommonEnum::STATE_IS_FAIL) {
            return true;
        }
        $accounts = $this->getAccountBalance($companyId, $staffId);
        $data = [];
        foreach ($accounts as $k => $v) {
            if ($v['balance'] >= $money) {
                array_push($data, [
                    'account_id' => $v['id'],
                    'company_id' => $companyId,
                    'consumption_date' => $consumptionDate,
                    'location_id' => $canteenId,
                    'used' => CommonEnum::STATE_IS_OK,
                    'status' => CommonEnum::STATE_IS_OK,
                    'staff_id' => $staffId,
                    'type' => $type,
                    'order_id' => $orderId,
                    'money' => 0 - $money,
                    'outsider' => $outsider,
                    'type_name' => $typeName
                ]);
                break;
            } else {
                if ($v['balance'] > 0) {
                    array_push($data, [
                        'account_id' => $v['id'],
                        'company_id' => $companyId,
                        'consumption_date' => $consumptionDate,
                        'location_id' => $canteenId,
                        'used' => CommonEnum::STATE_IS_OK,
                        'status' => CommonEnum::STATE_IS_OK,
                        'staff_id' => $staffId,
                        'type' => $type,
                        'order_id' => $orderId,
                        'money' => 0 - $v['balance'],
                        'outsider' => $outsider,
                        'type_name' => $typeName
                    ]);
                    $money -= $v['balance'];
                }

            }
        }
        $res = (new AccountRecordsT())->saveAll($data);
        if (!$res) {
            throw new SaveException(['msg' => '账户明细失败']);
        }

    }

    public function getAccountBalance($companyId, $staffID)
    {
        //获取企业所有账户
        $accounts = CompanyAccountT::accountsWithSorts($companyId);
        //获取用户账户余额
        $accountBalance = AccountRecordsT::statistic($staffID);
        foreach ($accounts as $k => $v) {
            $balance = 0;
            foreach ($accountBalance as $k2 => $v2) {
                if ($v['id'] == $v2['account_id']) {
                    $balance += $v2['money'];
                }

            }
            $accounts[$k]['balance'] = $balance;
        }
        return $accounts;

    }

    public function clearAccounts()
    {

        \think\facade\Db::startTrans();
        try {
            //获取需要清除余额的账户
            $account = CompanyAccountT::clearAccounts();
            if (!count($account)) {
                return true;
            }
            foreach ($account as $k => $v) {
                $accountId = $v['id'];
                //检测是否清零时间
                if (!$this->checkClearTime($v['next_time'])) {
                    continue;
                }
                $clearData = [];
                //获取账户所有用户的余额
                $staffBalance = AccountRecordsT::staffBalance($accountId);
                if (!count($staffBalance)) {
                    continue;
                }
                foreach ($staffBalance as $k2 => $v2) {
                    if (abs($v2['money']) > 0) {
                        array_push($clearData, [
                            'account_id' => $accountId,
                            'company_id' => $v2['company_id'],
                            'consumption_date' => date('Y-m-d'),
                            'location_id' => 0,
                            'used' => CommonEnum::STATE_IS_OK,
                            'status' => CommonEnum::STATE_IS_OK,
                            'staff_id' => $v2['staff_id'],
                            'type' => 'clear',
                            'order_id' => 0,
                            'money' => 0 - $v['balance'],
                            'outsider' => 2,
                            'type_name' => "到期清零"
                        ]);
                    }

                }
                if (count($clearData)) {
                    (new AccountRecordsT())->saveAll($clearData);
                }
                //更新清零时间
                $nextTime = $this->getNextClearTime($v['clear_type'],
                    $v['first'], $v['end'],
                    $v['day_count'], $v['time_begin']);
                CompanyAccountT::update(['next_time' => $nextTime], ['id' => $accountId]);
            }

            \think\facade\Db::commit();
        } catch (\Exception $e) {
            LogT::saveInfo("账户清零失败：" . $e->getMessage());

            \think\facade\Db::rollback();
        }
    }

    private function checkClearTime($nextTime)
    {
        $now = strtotime(date('Y-m-d H:i'));
        $nextTime = strtotime(date('Y-m-d H:i', strtotime($nextTime)));
        if ($now == $nextTime) {
            return true;
        }
        return false;

    }

    private function getNextClearTime($clearType, $first, $end, $dayCount, $time_begin)
    {
        if ($clearType == "day") {
            return addDay($dayCount, $time_begin) . ' ' . "23:59";
        }
        if ($clearType == "week") {
            if ($first == CommonEnum::STATE_IS_OK) {
                if (date('w') == 1) {

                    return addDay(7, date('Y-m-d')) . ' ' . "00:01";
                } else {
                    return date('Y-m-d', strtotime('+1 week last monday')) . ' ' . "00:01";
                }
            } else if ($end == CommonEnum::STATE_IS_OK) {
                if (date('w') == 0) {
                    return date('Y-m-d') . ' ' . "23:59";
                } else {
                    return date('Y-m-d', strtotime('+1 week last sunday')) . ' ' . "23:59";
                }
            }
        } else if ($clearType == "month") {
            if ($first == CommonEnum::STATE_IS_OK) {
                $nextMonthBegin = date('Y-m-01', strtotime('+1 month'));
                return $nextMonthBegin . ' ' . "00:01";
            } else if ($end == CommonEnum::STATE_IS_OK) {
                $monthBegin = date('Y-m-01');
                return date('Y-m-d', strtotime("$monthBegin +1 month -1 day")) . ' ' . "23:59";
            }

        } else if ($clearType == "quarter") {
            $season = ceil((date('n')) / 3);

            if ($first == CommonEnum::STATE_IS_OK) {
                $nextQuarterBegin = date('Y-m-01', mktime(0, 0, 0, ($season) * 3 + 1, 1, date('Y')));
                return $nextQuarterBegin . ' ' . "00:01";
            } else if ($end == CommonEnum::STATE_IS_OK) {
                return date('Y-m-d', mktime(23, 59, 59, $season * 3,
                    date('t', mktime(0, 0, 0, $season * 3, 1,
                        date("Y"))), date('Y')));
            }

        } else if ($clearType == "year") {
            $nextYearBegin = date('Y-01-01', strtotime('+1 year'));

            if ($first == CommonEnum::STATE_IS_OK) {
                return $nextYearBegin . ' ' . "00:01";
            } else if ($end == CommonEnum::STATE_IS_OK) {
                return reduceDay(1, $nextYearBegin) . ' ' . "23:59";
            }
        }
    }


}