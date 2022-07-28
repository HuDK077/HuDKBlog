<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\Member;
use Illuminate\Http\Request;

class ApiMemberController extends Controller
{


    /**
     * showdoc
     * @catalog 前台/用户
     * @title 更新用户信息接口_v1.0
     * @description 更新用户信息接口_v1.0
     * @method POST
     * @url http://xx.com/api/member/updateMember
     * @header token 必选 string 设备token
     * @param openid 必选 string 用户openid
     * @param userInfo 必选 array 用户信息
     * @return {"error_code": 2001,"message": "更新成功"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/23
     * @TIME: 1:58 下午
     */
    public function updateMember(Request $request){
        if (!$request->userInfo || !$request->openId) {
            return ['error_code' => 2004, 'msg' => '缺少参数'];
        }
        $userInfo = $request->userInfo;
        info("********************");
        info($userInfo);
        info($request->openId);
        info("********************");
        $temp = [
            'avatar' =>$userInfo['avatarUrl'],
            'nickname' =>$userInfo['nickName'],
            'city' =>$userInfo['city'],
            'province' =>$userInfo['province'],
            'gender' => $userInfo['gender']
        ];
        if (Member::where('openid',$request->openId)->first()) {
            info('用户存在');
            if (Member::where('openid',$request->openId)->update($temp)) {
                return ['error_code' => 2001,'message' => '更新成功'];
            }
            return ['error_code' => 2005,'message' => '更新失败'];
        }else{
            $temp['openid'] = $request->openId;
            if (Member::create($temp)) {
                return ['error_code' => 2001,'message' => '更新成功'];
            }
            return ['error_code' => 2005,'message' => '更新失败'];
        }
    }

    /**
     * showdoc
     * @catalog 前台/用户
     * @title 获取用户信息接口_v1.0
     * @description 获取用户信息接口_v1.0
     * @method POST
     * @url http://xx.com/api/member/getMember
     * @header token 必选 string 设备token
     * @param openid 必选 string 用户openid
     * @return {"error_code": 2001,"message": "success","data": {"id": 1,"openid": "owtNA5SrDdC83SDSFj7Z5AVjVvgA","unionid": null,"nickname": null,"avatar": null,"gender": null,"province": null,"city": null,"phone": null,"created_at": "2020-09-23 15:13:05","updated_at": "2020-09-23 15:13:05"}}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @return_param data array 用户信息
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/23
     * @TIME: 2:33 下午
     */
    public function getMember(Request $request){
        $openid=$request->openid;
        if (empty($openid)){
            return ['error_code'=>2004,'message'=>"缺少必要参数"];
        }
        $user= Member::where('openid',$openid)->first();
        if (empty($user)){
            return ['error_code'=>2002,'message'=>"没有该用户"];
        }else{
            return ['error_code'=>2001,'message'=>"success",'data'=>$user];
        }
    }

}
