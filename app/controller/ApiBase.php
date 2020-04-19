<?php
/**
 * 支付pay 服务 公共API
 */

namespace app\controller;

use app\BaseController;
use app\lib\exception\ParameterException;
use app\lib\Show;
use think\exception\HttpResponseException;

class ApiBase extends BaseController
{

    public $payType = "";

    public function initialize()
    {
        $payType = input("param.pay_type", "", "trim");
        if (!in_array($payType, config("pay.pay_types"))) {

            return json(new ParameterException(['msg' => "支付类型参数错误"]));
        }

        $this->payType = $payType;

    }
}
