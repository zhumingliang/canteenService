<?php


namespace app\task;


use app\business\AccountBusiness;
use app\business\FoodBusiness;
use app\model\LogT;
use app\model\TaskLogT;
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
        TaskLogT::create(['content' => '清除账户信息']);
        (new AccountBusiness())->clearAccounts();
        TaskLogT::create(['content' => '自动上架菜品']);
        (new FoodBusiness())->autoUpFoods();
    }
}