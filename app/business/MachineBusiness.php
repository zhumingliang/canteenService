<?php


namespace app\business;


use app\lib\Http;
use app\model\CompanyAccountT;
use app\model\MachineT;
use app\model\TaskLogT;
use think\facade\Config;

class MachineBusiness
{
    public function checkMachineOnline()
    {

        try {
            TaskLogT::create(['content' => '消费机在线检测开始']);
            $machines = MachineT::check();
            $returnId = '';
            if ($machines) {
                $data = [];
                foreach ($machines as $k => $v) {
                    array_push($data, $v['id']);

                }
                if (count($data)) {
                    $returnId = implode(',', $data);
                }
            }
            if (strlen($returnId)) {
                $sendData = [
                    'type' => 'machine',
                    'id' => $returnId
                ];
                $url = Config::get('setting.sendTemplateUrl');
                $res = Http::post($url, $sendData);
                $sendData['res'] = $res;
                TaskLogT::create(['content' => '消费机在线检测通知：' . json_encode($sendData)]);

            }
        } catch (\Exception $e) {
            TaskLogT::create(['content' => '消费机在线检测通知：' . $e->getMessage()]);
        }


    }

}