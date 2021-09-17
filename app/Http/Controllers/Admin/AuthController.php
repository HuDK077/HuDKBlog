<?php
/**
 * Created by PhpStorm.
 * User: cracker
 * Date: 2020/4/19
 * Time: 14:15
 * ProjectName: sport
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AdminUser;
use App\Models\Admin\Menu;
use App\Models\Admin\RoleMenu;
use App\Models\Admin\RoleUser;
use Illuminate\Http\Request;
use Exception;

class AuthController extends Controller{


    /**
     * showdoc
     * @catalog 后台/账号管理
     * @title 用户注册接口
     * @description HuDK
     * @method POST
     * @url http://xx.com/admin/auth/register
     * @param username 必选 string 用户名
     * @param password 必选 string 登录密码
     * @return {"success": true,"token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9xaXl1YW4uY2NcL2FkbWluXC9hdXRoXC9yZWdpc3RlciIsImlhdCI6MTYwMTAxNjUxOCwiZXhwIjoxNjAxMTAyOTE4LCJuYmYiOjE2MDEwMTY1MTgsImp0aSI6IjVxaEdBQVF5RDJ1Zk81WVUiLCJzdWIiOjMsInBydiI6IjA1NzdiNDFmNTRlMDk1YTNkOGNhMjM3NDM2NTAxYjhiN2IxZmU0YTQifQ.pOVbBv7SyLqjhRpflKEzuhMSxkYfLR_dQ-OErkPF518","token_type": "bearer","expires_in": 86400}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @return_param token string token
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/25
     * @TIME: 2:47 下午
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request){
        $this->validate($request,[
            'username' => 'required|unique:admin_users',
            'password' => 'required'
        ]);
        $data = ['username' => $request->username,'password' => bcrypt($request->password)];
        AdminUser::create($data);
        $input = $request->only("username","password");
        $jwt_token = null;
        $jwt_token = auth('admin')->attempt($input);
        if (!$jwt_token) {
            return response()->json(['success' => false,'message' => 'Invalid Name or Password'],401);
        }
        return response()->json([
            'error_code' => 2001,
            'success' => true,
            'token' => $jwt_token,
            'token_type' => 'bearer',
            'expires_in' => auth('admin')->factory()->getTTL() * 60
        ]);
    }

   /**
     * showdoc
     * @catalog 后台/账号管理
     * @title 用户登录接口_v1.0
     * @description HuDK
     * @method POST
     * @url http://xx.com/admin/auth/login
     * @param username 必选 string 用户名
     * @param password 必选 string 密码
     * @return {"success": true,"token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9xaXl1YW4uY2NcL2FkbWluXC9hdXRoXC9sb2dpbiIsImlhdCI6MTYwMDkxNDMwMCwiZXhwIjoxNjAxMDAwNzAwLCJuYmYiOjE2MDA5MTQzMDAsImp0aSI6IlFIb2p4cEQ2Yktva3FGc2IiLCJzdWIiOjEsInBydiI6IjA1NzdiNDFmNTRlMDk1YTNkOGNhMjM3NDM2NTAxYjhiN2IxZmU0YTQifQ.7wTcdHmPeQQqcK1UYUFHa6s2pFM2KdOuZlyiXOPN1-k","token_type": "bearer","expires_in": 86400}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @return_param token string token
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/24
     * @TIME: 10:22 上午
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request){
        $this->validate($request,[
            "username" => "required",
            "password" => "required"
        ]);
        $input = $request->only("username","password");
        $jwt_token = null;
        $jwt_token = auth('admin')->attempt($input);
        if (!$jwt_token) {
            return apiResponse(2401,[],'Invalid Name or Password');
        }
        return  ['error_code' => 2001,'success' => true, 'token' => $jwt_token, 'token_type' => 'bearer', 'expires_in' => auth('admin')->factory()->getTTL() * 60];
    }

    /**
     * showdoc
     * @catalog 后台/账号管理
     * @title 账号登出接口_v1.0
     * @description HuDK
     * @method POST
     * @url http://xx.com/admin/auth/loginOut
     * @header token 必选 string 设备token
     * @return {"error_code": 2001,"message": "success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/24
     * @TIME: 10:26 上午
     */
    public function loginOut(){
        $res = auth('admin')->logout(true);
        if (!$res){
            return response()->json(['error_code' => 2001,'message' => 'success']);
        }
    }

    /**
     * 获取当前登录者信息
     * showdoc
     * @catalog 后台/账号管理
     * @title 获取当前登录者信息_v1.0
     * @description HuDK
     * @method POST
     * @url http://xx.com/admin/auth/getAuthUser
     * @header token 必选 string 设备token
     * @return {"error_code": 2001,"message": "success","data": {"id": 1,"username": "admin","password": "$2y$10$YYTmU9jmXiEmBi0V/4yWbO2b5k3V/AwuC0vnbf9wxwuULTEV99HJO","name": "test","avatar": null,"remember_token": null,"created_at": null,"updated_at": null}}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @return_param member array 用户信息
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/24
     * @TIME: 10:28 上午
     */
    public function getAuthUser(Request $request)
    {
        $start = microtime(true);
        $member = auth('admin')->user();
        $role = RoleUser::where('user_id',$member->id)->first();
        if ($role->role_id == 1){   #如果是超级管理就返回所有页面权限
            $menus = Menu::orderBy('sort','desc')->pluck('slug');
        }else{
            $r_m = RoleMenu::where('role_id',$role->role_id)->pluck('menu_id'); #菜单ID组
            $menus = Menu::whereIn('id',$r_m)->orderBy('sort','desc')->pluck('slug');
        }
        $end = microtime(true);
        return response()->json(['error_code' => 2001,'message' => 'success','data' => ['member' => $member,'menus' => $menus,'role' => $role,'runtime' => $end-$start]]);
    }


    /**
     * showdoc
     * @catalog 后台/账号管理
     * @title 修改账号密码接口_v1.0
     * @description HuDK
     * @method POST
     * @url http://xx.com/admin/auth/resetPaw
     * @header token 必选 string 设备token
     * @param password 必选 string  新密码
     * @return {"error_code": 2001,"data": [],"message": "success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/25
     * @TIME: 4:04 下午
     * @throws \Illuminate\Validation\ValidationException
     */
    public function resetPaw(Request $request){
        $this->validate($request,['password' => 'required']);
        $member = auth('admin')->user();
        $data = ['password' => bcrypt($request->password)];
        try{
            AdminUser::where('id',$member->id)->update($data);
            return apiResponse(2001);
        }catch (Exception $exception){
            return apiResponse(2005,[],$exception->getMessage());
        }
    }


    /**
     * showdoc
     * @catalog 后台/账号管理
     * @title 登录认证_v1.0
     * @description HuDK
     * @method POST
     * @url http://xx.com/admin/auth/authentication
     * @header token 必选 string 设备token
     * @return {"error_code": 2001,"data": [],"message": "success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/25
     * @TIME: 4:04 下午
     */
    public function authentication(){
        $token = auth('admin')->getToken();
        if (!$token) {
            return apiResponse(2005);
        }
        $user = auth('admin')->user();
        if (!$user) {
            return apiResponse(2005);
        }
        return apiResponse(2001);
    }
}
