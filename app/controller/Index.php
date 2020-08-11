<?php

namespace app\controller;

use app\BaseController;
use app\business\BackupBusiness;
use app\business\ReceptionBusiness;
use app\model\AuthT;
use app\model\LogT;
use think\facade\Cache;
use think\Request;

class Index extends BaseController
{
    public function index(Request $request)
    {
        (new BackupBusiness())->backupMysql();

        //echo  array_sum(array_column([['price'=>1],['price'=>3]], 'price'));
    }

    public function hello($name = 'ThinkPHP6')
    {

        //  return 'hello,' . $name;
    }
}
