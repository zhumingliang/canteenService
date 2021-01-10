<?php


namespace app\model;


use app\lib\enum\CommonEnum;
use think\Model;

class AutomaticT extends Model
{
    public function foods()
    {
        return $this->hasMany('AutomaticFoodT', 'auto_id', 'id');
    }

    public static function auto($w)
    {
        return self::where('auto_week',$w)
            ->where('state',CommonEnum::STATE_IS_OK)
            ->with([
                'foods' => function ($query) {
                    $query->where('state', CommonEnum::STATE_IS_OK);
                }
            ])
            ->hidden(['create_time', 'update_time'])
            ->select()->toArray();

    }
}