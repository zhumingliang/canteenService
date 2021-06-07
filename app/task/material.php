<?php


namespace app\task;


use app\model\TaskLogT;
use yunwuxin\cron\Task;

class material extends Task
{
    public function configure()
    {
        $this->everyThirtyMinutes();
    }


    protected function execute()
    {
        try {
            TaskLogT::create(['content' => "统计材料开始："]);
             (new \app\business\Material())->orderMaterials();
        } catch (\Exception $e) {
            TaskLogT::create(['content' => "统计材料失败：" . $e->getMessage()]);
        }
    }
}