<?php


namespace app\business;


use app\lib\Http;
use app\model\CompanyAccountT;
use app\model\MachineT;
use app\model\TaskLogT;

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
                    'type'=>'machine',
                    'id' => $returnId
                ];
                print_r($sendData);
               // $url = config('setting.domain').'/api/v1/service/template';
                $url ='http://test-api.51canteen.cn/api/v1/service/template';
                echo $url;
                $res = Http::post($url, $sendData);
                print_r($res);
                TaskLogT::create(['content' => '消费机在线检测通知1：' . json_encode($res)]);

            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            TaskLogT::create(['content' => '消费机在线检测通知2：' . $e->getMessage()]);
        }


    }

}