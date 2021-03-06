<?php


namespace app\model;


use app\lib\enum\CommonEnum;
use think\Model;

class AccountRecordsT extends Model
{
    public static function statistic($staff_id)
    {
        $statistic = self::where('staff_id', $staff_id)
            ->where('state', CommonEnum::STATE_IS_OK)
            ->field('account_id,sum(money) as money')
            ->group('account_id')
            ->select();
        return $statistic;

    }

    public static function staffBalance($accountId)
    {
        $statistic = self::where('account_id', $accountId)
            ->where('state', CommonEnum::STATE_IS_OK)
            ->field('company_id,staff_id,sum(money) as money')
            ->group('staff_id')
            ->select();
        return $statistic;
    }
}