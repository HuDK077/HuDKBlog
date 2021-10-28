<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Request as sRequest;


class ApiPayController extends Controller
{
    public $app;

    public function __construct()
    {
        parent::__construct();
        $this->app = app("wechat.payment");
    }


    /**
     * showdoc
     * @catalog 前台/微信支付
     * @title 发起支付
     * @description 发起支付
     * @method POST
     * @url http://xx.com/api/pay/pay
     * @header token 必选 string 设备token
     * @param openid 必选 string openid
     * @param orderid 必选 string 订单号
     * @return {"error_code": 2001,"data": xxxx,"message": "success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @return_param name string 用户名称
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/10/28
     * @TIME: 4:51 PM
     */
    public function pay(Request $request)
    {
        $openid = $request->openid;
        $orderid = $request->orderid;
        if (empty($openid) || empty($orderid)) {
            return ['error_code' => 2004, 'message' => "缺少必要参数"];
        }
        $model = explode('-', $orderid)[0];
        switch ($model) {
            case 'PO'://产品下单支付发起
                //todo
            default:
                return ['error_code' => 2005, 'message' => '支付调起失败'];
        }
    }


    //支付回调入口
    public function payResult(Request $request)
    {
        info('进入支付回调');
        $get = request()->query();
        $post = request()->post();
        $cookie = request()->cookie();
        $files = request()->file();
        $server = request()->server();
        $xml = request()->getContent();
        $this->app["request"] = new sRequest($get,$post,[],$cookie,$files,$server,$xml);
        $response = $this->app->handlePaidNotify(function ($message, $fail) {
            $orderNum = $message['out_trade_no'];//系统订单号
            $model = explode('-', $orderNum)[0];
            switch ($model) {
                case 'PO':
                    //todo
            }
        });
        return $response;
    }


    //获取支付参数
    protected function payUnit($orderNum, $price, $openid)
    {
        $title = "屺源山泉";//todo
        $result = $this->app->order->unify([
            'body' => $title,
            'out_trade_no' => $orderNum,
            'total_fee' => (int)($price * 100),
            'spbill_create_ip' => '', // 可选，如不传该参数，SDK 将会自动获取相应 IP 地址
            'notify_url' => env('APP_URL') . '/api/pay/payResult', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'trade_type' => 'JSAPI', // 请对应换成你的支付方式对应的值类型
            'openid' => $openid,
        ]);
        info($result);
        if ($result['return_code'] == 'FAIL') {
            return ['error_code' => 2005, 'message' => $result['return_msg']];
        } else if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS') {
            $result = $this->app->jssdk->sdkConfig($result['prepay_id']);                   //二次签名，一定要做不然报签名错误
            return ['error_code' => 2001, 'message' => 'success', 'result' => $result];
        } elseif ($result['return_code'] == 'SUCCESS' || $result['result_code'] == 'FAIL') {
            return $result;
        } else {
            return ['error_code' => 2006, 'message' => '微信支付签名失败'];
        }
    }

}
