<?php


namespace app\model;


use app\lib\enum\CommonEnum;
use think\Model;
use function JmesPath\search;

class CompanyAccountT extends Model
{
    public static function accountsWithSorts($companyId)
    {
        $accounts = self::where('company_id', $companyId)
            ->where('state', CommonEnum::STATE_IS_OK)
            ->field('id,name,sort,type,fixed_type')
            ->order('sort')
            ->select()->toArray();
        return $accounts;
    }

    public static function clearAccounts()
    {
        $accounts = self::where('clear', '>', 1)
            ->where('state', CommonEnum::STATE_IS_OK)
            ->select()->toArray();
        return $accounts;
    }

}