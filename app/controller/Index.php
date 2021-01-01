<?php

namespace app\controller;

use app\BaseController;
use app\business\AccountBusiness;
use app\business\BackupBusiness;
use app\business\MachineBusiness;
use app\business\OrderBusiness;
use app\business\ReceptionBusiness;
use app\lib\exception\SuccessMessageWithData;
use app\model\AccountRecordsT;
use app\model\AuthT;
use app\model\LogT;
use think\facade\Cache;
use think\Request;
use function AlibabaCloud\Client\envConversion;

class Index extends BaseController
{
    public function index(Request $request)
    {
        (new MachineBusiness())->checkMachineOnline();
    }

    public function hello($name = 'ThinkPHP6')
    {

        //  return 'hello,' . $name;
    }



}
