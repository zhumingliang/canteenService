<?php


namespace app\model;


use app\lib\enum\CommonEnum;
use think\console\command\optimize\Schema;
use think\Model;

class PunishmentStrategyT extends Model
{
    public function detail()
    {
        return $this->hasOne('PunishmentDetailT', 'strategy_id', 'id');
    }

    public static function punishment($companyId, $staffTypeId)
    {
        return self::where('company_id', $companyId)
            ->where('staff_type_id', $staffTypeId)
            ->with([
                'detail' => function ($query) {
                    $query->where('type','no_meal ')
                        ->where('state', CommonEnum::STATE_IS_OK)
                        ->field('id,strategy_id,count');
                }
            ])->find()->toArray();

    }

}