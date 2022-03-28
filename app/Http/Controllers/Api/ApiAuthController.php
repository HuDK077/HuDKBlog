<?php
/**
 * Created by PhpStorm.
 * User: HuDK
 * Date: 2020/4/19
 * Time: 14:15
 * ProjectName: sport
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\Member;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Tymon\JWTAuth\JWTAuth;

class ApiAuthController extends Controller
{
    /**
     * showdoc
     * @catalog 前台/用户
     * @title 注册
     * @description 用户注册
     * @method POST
     * @url http://xx.com/api/auth/register
     * @param phone 必选 string 登录手机号
     * @param password 非必选 string 密码
     * @param verificationCode 非必选 string 验证码
     * @return {"error_code": 2001,"data": [],"message": "success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @return_param token string token
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2022/3/23
     * @TIME: 11:06 上午
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required',
            'password' => 'required',
            'verificationCode' => 'required',
        ]);
        $cipher = Hash::make($request->password) . env('JWT_SECRET');
        $data = [
            'phone' => $request->phone,
            'password' => $cipher,
        ];
        // 获取验证码todo
        $verificationCode = Redis::get($request->phone);
        if (!$verificationCode) {
            return apiResponse('2005', [], '验证码过期');
        }
        if ($verificationCode != $request->verificationCode) {
            return apiResponse('2005', [], '验证码错误');
        }
        $user = Member::query()->where(['phone' => $request->phone])->first();
        if ($user) {
            return apiResponse(2005, [], '注册失败,手机号已被注册');
        }
        $member = Member::insert($data);
        if ($member) {
            return apiResponse(2001);
        } else {
            return apiResponse(2005, [], '注册失败');
        }
    }

    /**
     * showdoc
     * @catalog 前台/用户
     * @title 忘记密码
     * @description 忘记密码
     * @method POST
     * @url http://xx.com/api/auth/resetPassword
     * @param phone 必选 string 登录手机号
     * @param password 非必选 string 密码
     * @param verificationCode 非必选 string 验证码
     * @return {"error_code": 2001,"data": [],"message": "success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @return_param token string token
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2022/3/23
     * @TIME: 11:06 上午
     */
    public function resetPassword(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required',
            'password' => 'required',
            'verificationCode' => 'required',
        ]);
        $cipher = Hash::make($request->password) . env('JWT_SECRET');
        // 获取验证码todo
        $verificationCode = Redis::get($request->phone);
        if (!$verificationCode) {
            return apiResponse('2005', [], '验证码过期');
        }
        if ($verificationCode != $request->verificationCode) {
            return apiResponse('2005', [], '验证码错误');
        }
        $user = Member::query()->where(['phone' => $request->phone])->first();
        if (!$user) {
            return apiResponse(2005, [], '用户不存在,请注册');
        }
        $user->update(['password' => $cipher]);
        if ($user->save()) {
            return apiResponse(2001);
        } else {
            return apiResponse(2005, [], '注册失败');
        }
    }

    /**
     * showdoc
     * @catalog 前台/用户
     * @title 用户获取token接口_v1.0
     * @description 用户获取token接口_v1.0
     * @method POST
     * @url http://xx.com/api/auth/login
     * @param phone 必选 string 登录手机号
     * @param password 非必选 string 密码
     * @param verificationCode 非必选 string 验证码
     * @param loginType 必选 int 登录类型：1=>密码登录，2=>验证码登录
     * @return {"success": true,"token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9xaXl1YW4uY2NcL2FwaVwvYXV0aFwvbG9naW4iLCJpYXQiOjE2MDA4MzI4ODksImV4cCI6MTYwMDkxOTI4OSwibmJmIjoxNjAwODMyODg5LCJqdGkiOiI5QlduZk5CeUZKN3FORGNyIiwic3ViIjoxLCJwcnYiOiJiNGI3NjMyZjkwNWYwOGM4NjI3YjRlNmNmM2RmNmE4NWU4NWM0MjhlIn0.egfHzHTEgRV0eSlEsBoXstMjqf4Tfw4Wc35eGIl_95A","token_type": "bearer","expires_in": 86400}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @return_param token string token
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/23
     * @TIME: 11:41 上午
     */
    public function login(Request $request)
    {
        #region 使用用户账号密码进行认证
        $this->validate($request, [
            'phone' => 'required|regex:/^1[345789][0-9]{9}$/',
            'type' => 'required'
        ]);
        $member = Member::where(['phone' => $request->phone])->first();
        if (!$member) {
            return apiResponse(2005, [], '用户不存在');
        }
        // 密码登录
        if ($request->type == 1) {
            $count = strpos($member->password, env('JWT_SECRET'));
            $cipher = substr_replace($member->password, "", $count, strlen(env('JWT_SECRET')));
            if (!Hash::check($request->password, $cipher)) {
                return apiResponse(2005, [], '密码错误');
            }
        } else if ($request->type == 2) {
            // 获取验证码todo
            $verificationCode = Redis::get($request->phone);
            if (!$verificationCode) {
                return apiResponse('2005', [], '验证码过期');
            }
            if ($verificationCode != $request->verificationCode) {
                return apiResponse('2005', [], '验证码错误');
            }
        } else {
            return apiResponse('2005', [], '登录方式不存在');
        }
        $jwt_token = auth('api')->fromUser($member);
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
     * @author: HuDK
     * @DATE: 2020/6/2
     * @TIME: 13:50
     * @NAME: loginOut
     */
    public function loginOut(Request $request)
    {
        auth('api')->logout();
        return apiResponse(2005);
    }

    /**
     * 获取当前登录用户
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author: HuDK
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
