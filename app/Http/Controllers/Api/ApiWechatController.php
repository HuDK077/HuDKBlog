<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\Member;
use Illuminate\Http\Request;

class ApiWechatController extends Controller
{
    public $app;
    public function __construct()
    {
        $this->app = app('wechat.mini_program');
    }

    /**
     * showdoc
     * @catalog 前台/微信
     * @title 小程序用户登录接口_v1.0
     * @description 小程序用户登录接口_v1.0
     * @method POST
     * @url http://xx.com/api/wechat/login
     * @header token 必选 string 设备token
     * @param code 必选 string jscode
     * @return {"error_code": 2001,"message": "success","openid": "xxx"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @return_param openid string 用户openid
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/23
     * @TIME: 11:08 上午
     */
    public function login(Request $request)
    {
        if (!$request->code) {
            return response(['error_code' => 2004, 'msg' => '参数错误', 'data' => []]);
        }
        $ret = $this->app->auth->session($request->code);
        $openid = $ret['openid'];
        $member = Member::where('openid',$openid)->first();
        if (!$member) {
            Member::create(['openid' => $openid]);
        }
        return ['error_code' => 2001, 'message' => 'success', 'openid' => $openid];
    }


    /**
     * showdoc
     * @catalog 前台/微信
     * @title 获取微信绑定手机号_v1.0
     * @description 获取微信绑定手机号_v1.0
     * @method POST
     * @url http://xx.com/api/wechat/getPhoneByCode
     * @header token 必选 string 设备token
     * @param encryptedData 必选 string encryptedData
     * @param iv 必选 string iv
     * @return {"error_code": 2001,"message": "success","data": "xxx"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @return_param data array 用户手机号信息
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/23
     * @TIME: 11:18 上午
     */
    public function getPhoneByCode(Request $request)
    {
        $encryptedData= $request->encryptedData;
        $iv = $request->iv;
        if (!$encryptedData || !$iv || !$request->code) {
            return ['error_code' => 2004, 'message' => '缺少必要参数'];
        }
        $session = $this->app->auth->session($request->code);
        $sessionKey = $session['session_key'];
        $decryptedData = $this->app->encryptor->decryptData($sessionKey, $iv, $encryptedData);
        return ['error_code' => 2001, 'message' => 'success', 'data' => $decryptedData];
    }
}
