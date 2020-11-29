<?php

namespace app\controller;

use app\BaseController;
use app\business\BackupBusiness;
use app\business\OrderBusiness;
use app\business\ReceptionBusiness;
use app\model\AuthT;
use app\model\LogT;
use think\facade\Cache;
use think\Request;

class Index extends BaseController
{
    public function index(Request $request)
    {
      //  (new BackupBusiness())->backupMysql();

        try {
            (new OrderBusiness())->handelUnusedOrder();
            (new ReceptionBusiness())->handelReception();
            // (new BackupBusiness())->backupMysql();
        } catch (\Exception $e) {
            LogT::saveInfo("批量处理未订餐就餐失败：" . $e->getMessage());
        }
    }

    public function hello($name = 'ThinkPHP6')
    {

        //  return 'hello,' . $name;
    }
}
