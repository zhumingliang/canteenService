<?php


namespace app\model;


use think\Model;

class CompanyT extends Model
{
    public static function company($id)
    {
        return self::where('id', $id)->find();
    }

}