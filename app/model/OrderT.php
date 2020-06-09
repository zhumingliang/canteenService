<?php


namespace app\model;


use app\lib\enum\CommonEnum;
use think\Model;

class OrderT extends Model
{
    public function dinner()
    {
        return $this->belongsTo('DinnerT', 'd_id', 'id');
    }



}