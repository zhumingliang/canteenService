<?php


namespace app\model;


use app\lib\enum\CommonEnum;
use think\Db;
use think\Model;

class FoodMaterialT extends Model
{
    public static function getSql($canteenId)
    {
        $subQuery = Db::table('canteen_food_material_t')
            ->alias('a')
            ->leftJoin('canteen_food_t b', 'a.f_id=b.id')
            ->where('b.c_id', $canteenId)
            ->field('a.f_id as food_id,a.count,b.c_id as canteen_id,a.name as material')
            ->buildSql();
        return $subQuery;
    }

    public static function foods($canteenId, $material)
    {
        $sql = self::getSql($canteenId);
        return Db::table($sql . ' a')
            ->where('material', $material)
            ->toArray();
    }

}