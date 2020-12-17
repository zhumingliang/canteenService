<?php


namespace app\task;


use app\business\AccountBusiness;
use app\model\CompanyAccountT;
use app\model\LogT;
use yunwuxin\cron\Task;

class clearAccount extends Task
{
    public function configure()
    {
        $this->dailyAt("20:00:01");
        //设置任务的周期，每天执行一次，更多的方法可以查看源代码，都有注释
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