<?php


namespace app\task;


use app\business\AccountBusiness;
use app\business\NextMonthPayBusiness;
use app\model\LogT;
use app\model\TaskLogT;
use yunwuxin\cron\Task;

class sendClearMsg extends Task
{
    public function configure()
    {
        $this->dailyAt("08:01");
    }

    /**
     * 执行任务
     * @return mixed
     */
    protected function execute()
    {
        TaskLogT::create(['content' => '清零通知']);
        (new AccountBusiness())->checkClearAccountAndSendTemplate();

        TaskLogT::create(['content' => '发送缴费提醒消息']);
        (new NextMonthPayBusiness())->remind();
    }
}