<?php


namespace app\task;


use app\business\OrderBusiness;
use app\model\LogT;
use yunwuxin\cron\Task;

class clearOrder extends Task
{

    public function configure()
    {
        $this->everyMinute(); //设置任务的周期，每天执行一次，更多的方法可以查看源代码，都有注释
    }

    /**
     * 执行任务
     * @return mixed
     */
    protected function execute()
    {
        LogT::saveInfo(date('Y-m-d H:i:s'));
        try {
            (new OrderBusiness())->handelUnusedOrder();
        } catch (\Exception $e) {
            LogT::saveInfo("批量处理未订餐就餐失败：" . $e->getMessage());

        }

    }
}