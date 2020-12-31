<?php


namespace app\task;


use app\business\AccountBusiness;
use app\business\MachineBusiness;
use app\model\LogT;
use app\model\TaskLogT;
use yunwuxin\cron\Task;

class checkMachine3 extends Task
{
    public function configure()
    {
        $this->dailyAt("17:00");
    }

    /**
     * 执行任务
     * @return mixed
     */
    protected function execute()
    {
        TaskLogT::create(['content' => '设备在线检测']);
        (new MachineBusiness())->checkMachineOnline();
    }
}