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
