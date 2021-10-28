<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AdminRoleMenu;
use App\Models\Admin\AdminRoleWidget;
use App\Models\Admin\AdminUser;
use App\Models\Admin\AdminRoleUser;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    /**
     * showdoc
     * @catalog 后台/账号管理
     * @title 获取账号详情
     * @description 获取账号详情
     * @method POST
     * @url http://xx.com/admin/user/getUser
     * @header token 必选 string 设备token
     * @param id 必选 int 账号ID
     * @return {"id": 3,"username": "tempUser","name": "张三","avatar": "e7ff8f96f6e292e953afd142e45f4954","created_at": "2020-09-29 09:45:52","role_name": "临时账号","slug": "temporary"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @return_param username string 账号登录名
     * @return_param name string 账号名
     * @return_param avatar string 头像
     * @return_param role_name string 角色
     * @return_param slug string 角色标识
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/29
     * @TIME: 9:49 上午
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getUser(Request $request)
    {
        $this->validate($request, [
            'id' => 'required'
        ]);
        $user = AdminUser::with('roles')
            ->where(function ($query) use ($request) {
                $query->where('admin_users.id', $request->id);
            })->select(
                'admin_users.id',
                'admin_users.username',
                'admin_users.name',
                'admin_users.avatar',
                DB::raw("CONCAT('" . env('QINIU_DOMAIN_FULL') . "',avatar) as avatar_src"),
                'admin_users.created_at'
            )->first();
        return apiResponse(2001, $user);
    }

    /**
     * 添加后台账号
     * showdoc
     * @catalog 后台/账号管理
     * @title 添加后台账号
     * @description 添加后台账号
     * @method POST
     * @url http://xx.com/admin/user/addUser
     * @header token 必选 string 设备token
     * @param username 必选 string 用户登录名
     * @param password 必选 string 用户登录密码
     * @param role_id 必选 int 角色ID
     * @param avatar 非必选 string 头像
     * @param name 非必选 string 用户名
     * @return {"error_code": 2001,"data": {},"message": "success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/28
     * @TIME: 2:17 下午
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addUser(Request $request)
    {
        if ($this->GDT('user')) {
            return response('', 403);
        }
        $this->validate($request, [
            'username' => 'required|unique:admin_users',
            'name' => 'required',
            'role_id' => 'required',
            'password' => 'required'
        ]);
        $data = ['username' => $request->username, 'password' => bcrypt($request->password), 'name' => $request->name, 'avatar' => $request->avatar];
        DB::beginTransaction();
        try {
            $res = AdminUser::create($data);
            $role_user = ['role_id' => $request->role_id, 'user_id' => $res->id];
            AdminRoleUser::create($role_user);
            DB::commit();
            return apiResponse(2001);
        } catch (Exception $exception) {
            DB::rollBack();
            return apiResponse(2005, $exception->getMessage());
        }
    }

    /**
     * 获取所有后台用户接口
     * showdoc
     * @catalog 后台/账号管理
     * @title  获取所有后台用户接口
     * @description 获取所有后台用户接口
     * @method POST
     * @url http://xx.com/admin/user/getAllUser
     * @header token 必选 string 设备token
     * @param limit 非必选 int 分页
     * @param search 非必选 string 搜索字段
     * @return {"error_code": 2001,"data": {"current_page": 1,"data": [{"id": 1,"username": "admin","name": "超级管理员","avatar": null,"created_at": null}],"first_page_url": "http://qiyuan.cc/admin/user/getAllUser?page=1","from": 1,"last_page": 1,"last_page_url": "http://qiyuan.cc/admin/user/getAllUser?page=1","next_page_url": null,"path": "http://qiyuan.cc/admin/user/getAllUser","per_page": 15,"prev_page_url": null,"to": 1,"total": 1},"message": "success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @return_param id int 用户id
     * @return_param username string 用户登录名
     * @return_param name string 用户名
     * @return_param avatar string 头像
     * @return_param created_at string 创建时间
     * @return_param roles array 角色组，包含以下4个字段
     * @return_param id int 角色id
     * @return_param name string 角色名称
     * @return_param slug string 角色标签
     * @return_param default string 1默认角色，2非默认
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/27
     * @TIME: 3:32 下午
     */
    public function getAllUser(Request $request)
    {
        $limit = $request->limit ? $request->limit : 15;  #分页
        $users = AdminUser::with(['roles' => function ($query) {
                $query->select('id', 'name', 'slug', 'default');
            }]
        )->where(function ($query) use ($request) {
            $search = $request->search;
            if ($search) {
                $query->where('admin_users.username', 'like', '%' . $search . '%')
                    ->orwhere('admin_users.name', 'like', '%' . $search . '%');
            }
        })->select(
            'admin_users.id',
            'admin_users.username',
            'admin_users.name',
            'admin_users.avatar',
            DB::raw("CONCAT('" . env('QINIU_DOMAIN_FULL') . "',avatar) as avatar_src"),
            'admin_users.created_at'
        )->paginate($limit);
        return apiResponse(2001, $users);
    }

    /**
     * 删除账号接口
     * showdoc
     * @catalog 后台/账号管理
     * @title  删除账号接口
     * @description 删除账号接口
     * @method POST
     * @url http://xx.com/admin/user/delUser
     * @header token 必选 string 设备token
     * @param id 必选 int 账号ID
     * @return {"error_code": 2001,"data": [],"message": "success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/28
     * @TIME: 2:59 下午
     * @throws \Illuminate\Validation\ValidationException
     */
    public function delUser(Request $request)
    {
        $this->validate($request, [
            'id' => 'required'
        ]);
        DB::beginTransaction();
        try {
            AdminUser::where('id', $request->id)->delete();      #删除账号
            AdminRoleUser::where('user_id', $request->id)->delete();  #删除账号与角色的关联
            DB::commit();
            return apiResponse(2001);
        } catch (Exception $exception) {
            DB::rollBack();
            return apiResponse(2005, $exception->getMessage());
        }
    }

    /**
     * 修改账号信息-管理员用
     * showdoc
     * @catalog 后台/账号管理
     * @title 修改账号信息-管理员用
     * @description 修改账号信息-管理员用
     * @method POST
     * @url http://xx.com/admin/user/updateUser
     * @header token 必选 string 设备token
     * @param id 必选 int 用户ID
     * @param username 必选 string 用户登录名
     * @param name 必选 string 用户展示名
     * @param avatar 必选 string 用户头像
     * @param role_id 必选 array 角色ID数组
     * @param password 非必选 string 用户密码
     * @return  {"error_code": 2001,"data": {},"message": "success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/28
     * @TIME: 2:24 下午
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateUser(Request $request)
    {
        $this->validate($request, [
            'id' => "required",
            'role_id' => "required|array",
            'username' => "required",
            'name' => "required",
            'avatar' => "required",
        ]);
        $data = ['username' => $request->username, 'name' => $request->name, 'avatar' => $request->avatar];
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }
        try {
            return DB::transaction(function () use ($request, $data) {
                try {
                    AdminUser::where('id', $request->id)->update($data);
                    AdminRoleUser::where('user_id', $request->id)->delete();
                    $u_r = [];
                    foreach ($request->role_id as $role_id) {
                        array_push($u_r, ['user_id' => $request->id, 'role_id' => $role_id]);
                    }
                    AdminRoleUser::insert($u_r);
                    return apiResponse(2001);
                } catch (\Exception $exception) {
                    throw new \Exception($exception->getMessage());
                }
            });
        } catch (\Exception $exception) {
            return apiResponse(2005, [], $exception->getMessage());
        }
    }


    /**
     * showdoc
     * @catalog 后台/账号管理
     * @title 修改账号信息
     * @description 修改账号信息
     * @method POST
     * @url http://xx.com/api/user/editUser
     * @header token 必选 string 设备token
     * @param id 必选 int 用户id
     * @param name 必选 string 用户名
     * @param avatar 必选 string 头像
     * @return {"error_code": 2001,"data": [],"message": "success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/10/10
     * @TIME: 9:38 上午
     * @throws \Illuminate\Validation\ValidationException
     */
    public function editUser(Request $request)
    {
        $this->validate($request, [
            'id' => "required",
            'name' => "required",
            'avatar' => "required",
        ]);
        $data = ['id' => $request->id, 'name' => $request->name, 'avatar' => $request->avatar];
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }
        try {
            AdminUser::where('id', $request->id)->update($data);
            return apiResponse(2001);
        } catch (Exception $exception) {
            return apiResponse(2005, $exception->getMessage());
        }
    }

}
