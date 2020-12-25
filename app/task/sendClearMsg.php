<?php


namespace app\task;


use app\business\AccountBusiness;
use app\model\LogT;
use yunwuxin\cron\Task;

class sendClearMsg extends Task
{
    public function configure()
    {
        $this->dailyAt("08:00:01");
    }

    /**
     * 执行任务
     * @return mixed
     */
    protected function execute()
    {
        (new AccountBusiness())->checkClearAccountAndSendTemplate();
    }
}