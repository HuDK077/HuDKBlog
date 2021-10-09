<?php
/**
 * Created by PhpStorm.
 * User: cracker
 * Date: 2020/4/19
 * Time: 14:15
 * ProjectName: sport
 */
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\Member;
use Illuminate\Http\Request;
use Auth;
use Tymon\JWTAuth\JWTAuth;

class ApiAuthController extends Controller{

    /**
     * showdoc
     * @catalog 前台/用户
     * @title 用户获取token接口_v1.0
     * @description HuDK
     * @method POST
     * @url http://xx.com/api/auth/login
     * @param openId 必选 string 用户openid
     * @param userName 必选 string 用户名
     * @param passWord 必选 string 密码
     * @return {"success": true,"token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9xaXl1YW4uY2NcL2FwaVwvYXV0aFwvbG9naW4iLCJpYXQiOjE2MDA4MzI4ODksImV4cCI6MTYwMDkxOTI4OSwibmJmIjoxNjAwODMyODg5LCJqdGkiOiI5QlduZk5CeUZKN3FORGNyIiwic3ViIjoxLCJwcnYiOiJiNGI3NjMyZjkwNWYwOGM4NjI3YjRlNmNmM2RmNmE4NWU4NWM0MjhlIn0.egfHzHTEgRV0eSlEsBoXstMjqf4Tfw4Wc35eGIl_95A","token_type": "bearer","expires_in": 86400}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @return_param token string token
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/23
     * @TIME: 11:41 上午
     */
    public function login(Request $request){
        #region 使用用户模型进行认证
        $openId = $request->openId;
        if (!$openId){
            return response()->json(['success' => false,'message' => 'Invalid User'],401);
        }
        $member = Member::where('openid',$openId)->first();
        if (!$member){
            return response()->json(['success' => false,'message' => 'Invalid User'],401);
        }
        $jwt_token = auth('api')->fromUser($member);
        #endregion
        if (!$jwt_token) {
            return response()->json(['success' => false,'message' => 'Invalid Name or Password'],401);
        }
        return response()->json([
            'success' => true,
            'token' => $jwt_token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    /**
     * 登出
     * @param Request $request
     * @author: cracker
     * @DATE: 2020/6/2
     * @TIME: 13:50
     * @NAME: loginOut
     */
    public function loginOut(Request $request){
        $res = auth('api')->logout();
        return $res;
    }

    /**
     * 获取当前登录用户
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author: cracker
     * @DATE: 2020/6/2
     * @TIME: 13:50
     * @NAME: getAuthUser
     */
    public function getAuthUser(Request $request)
    {
        $member = auth('api')->user();
        return response()->json(['member' => $member]);
    }
}
