<?php


namespace app\task;


use app\business\OrderBusiness;
use app\business\ReceptionBusiness;
use app\model\TaskLogT;
use yunwuxin\cron\Task;

class Test extends Task
{

    public function configure()
    {
        $this->dailyAt("00:45");
    }

    /**
     * 执行任务
     * @return mixed
     */
    protected function execute()
    {
        try {
            TaskLogT::create(['content' => "开始测试："]);
           // (new OrderBusiness())->handelUnusedOrder();
        } catch (\Exception $e) {
            TaskLogT::create(['content' => "tong" . $e->getMessage()]);
        }

    }

}