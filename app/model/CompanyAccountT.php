<?php


namespace app\model;


use app\lib\enum\CommonEnum;
use think\Model;

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

}