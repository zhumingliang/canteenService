<?php


namespace app\task;


use app\business\NextMonthPayBusiness;
use app\model\LogT;
use app\model\TaskLogT;
use yunwuxin\cron\Task;


class getNextMonthPay extends Task
{
    public function configure()
    {
        $this->dailyAt("00:01"); //设置任务的周期，每月初执行一次
    }

    /**
     * 执行任务
     * @return mixed
     */
    protected function execute()
    {
        try {
            (new NextMonthPayBusiness())->handle();
        } catch (\Exception $e) {
            TaskLogT::create(['content' => "同步次月缴费数据失败：" . $e->getMessage()]);
        }

    }


}