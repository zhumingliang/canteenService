<?php


namespace app\model;


use app\lib\enum\CommonEnum;
use think\Db;
use think\Model;

class OrderDetailT extends Model
{
    public static function getSql($day, $foodId)
    {
        $subQuery = Db::table('canteen_oder_detail_t')
            ->alias('a')
            ->leftJoin('canteen_order_t b', 'a.o_id=b.id')
            ->where('a.f_id', $foodId)
            ->where('a.state', CommonEnum::STATE_IS_OK)
            ->where('b.state', CommonEnum::STATE_IS_OK)
            ->where('b.ordering_date', $day)
            ->field('a.count*b.count as food_count')
            ->buildSql();
        return $subQuery;
    }

    public static function foodCount($day, $foodId)
    {
        $sql = self::getSql($day, $foodId);
        return Db::table($sql . ' a')
            ->sum('food_count');
    }

}