<?php

namespace app\controller;

use app\BaseController;
use app\business\AccountBusiness;
use app\business\BackupBusiness;
use app\business\MachineBusiness;
use app\business\NextMonthPayBusiness;
use app\business\OrderBusiness;
use app\business\ReceptionBusiness;
use app\lib\exception\SuccessMessageWithData;
use app\model\AccountRecordsT;
use app\model\AuthT;
use app\model\LogT;
use app\model\TaskLogT;
use think\facade\Cache;
use think\Request;
use function AlibabaCloud\Client\envConversion;

class Index extends BaseController
{
    public function index(Request $request)
    {
        try {
            (new NextMonthPayBusiness())->handle();
        } catch (\Exception $e) {
            TaskLogT::create(['content' => "同步次月缴费数据失败：" . $e->getMessage()]);
        }
    }

    public function hello($name = 'ThinkPHP6')
    {

        //  return 'hello,' . $name;
    }



}
