<?php

namespace app\controller;

use app\BaseController;
use app\business\BackupBusiness;
use app\business\OrderBusiness;
use app\business\ReceptionBusiness;
use app\lib\exception\SuccessMessageWithData;
use app\model\AccountRecordsT;
use app\model\AuthT;
use app\model\LogT;
use think\facade\Cache;
use think\Request;

class Index extends BaseController
{
    public function index(Request $request)
    {
        $accountId = $request->param('account_id');
        $staffBalance = AccountRecordsT::staffBalance($accountId);
        return json(new SuccessMessageWithData(['data' => $staffBalance]));
    }

    public function hello($name = 'ThinkPHP6')
    {

        //  return 'hello,' . $name;
    }
}
