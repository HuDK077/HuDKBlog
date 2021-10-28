<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\ErrorLog;
use Illuminate\Http\Request;
use Exception;
class ErrorLogController extends Controller
{

    /**
     * 错误推送接口
     * showdoc
     * @catalog 异常/页面错误
     * @title 页面错误推送接口
     * @description 页面错误推送接口
     * @method POST
     * @url http://xx.com/admin/errorLog/errorPush
     * @header token 必选 string 设备token
     * @param error 必选 string 错误信息
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/10/12
     * @TIME: 11:19 上午
     * @throws \Illuminate\Validation\ValidationException
     */
    public function errorPush(Request $request){
        $user = auth('admin')->user();
        $data = [];
        if ($user){
            $data['user_id'] = $user['id'];
        }
        $this->validate($request,[
            'error' => 'required'
        ]);
        $data['error'] = json_encode($request->error);
        try{
            ErrorLog::create($data);
        }catch (Exception $exception){
            \Log::info($exception->getMessage());
        }
    }
}
