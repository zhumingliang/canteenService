<?php


namespace app\business;


use app\model\AccountRecordsT;
use app\model\CompanyAccountT;
use app\lib\enum\CommonEnum;
use app\lib\exception\SaveException;
use app\model\CompanyT;

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
                    'money' => 0-$money,
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


}