<?php


namespace app\task;


use app\business\AccountBusiness;
use app\model\LogT;
use yunwuxin\cron\Task;

class clearAccount extends Task
{
    public function configure()
    {
        $this->dailyAt("00:01");
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