<?php


namespace app\model;


use app\lib\enum\CommonEnum;
use think\Model;

class OrderParentT extends Model
{
    public function dinner()
    {
        return $this->belongsTo('DinnerT', 'dinner_id', 'id');
    }

    public function staff()
    {
        return $this->belongsTo('CompanyStaffT', 'staff_id', 'id');
    }


    public static function unUsedOutsiderOrder($consumption_time)
    {
        return self::where('used', CommonEnum::STATE_IS_FAIL)
            ->where('unused_handel', CommonEnum::STATE_IS_FAIL)
            ->where('type', 2)
            ->where('ordering_date', '<', $consumption_time)
            ->where('pay', 'paid')
            ->where('state', CommonEnum::STATE_IS_OK)
            ->with(
                [
                    'dinner' => function ($query) {
                        $query->field('id,name');
                    },
                    'staff' => function ($query) {
                        $query->field('id,status');
                    }
                ]
            )
            ->limit(0,100)
            ->select()
            ->toArray();
    }

}