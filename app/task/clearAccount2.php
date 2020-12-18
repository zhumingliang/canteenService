<?php


namespace app\task;


use app\business\AccountBusiness;
use app\model\CompanyAccountT;
use app\model\LogT;
use yunwuxin\cron\Task;

class clearAccount2 extends Task
{
    public function configure()
    {
        $this->dailyAt("23:50:00");
    }

    /**
     * 执行任务
     * @return mixed
     */
    protected function execute()
    {
        LogT::create(['content' => '清除账户信息']);
        (new AccountBusiness())->clearAccounts();
    }
}