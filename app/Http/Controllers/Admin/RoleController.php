<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AdminRole;
use App\Models\Admin\AdminRoleMenu;
use App\Models\Admin\AdminRoleUser;
use App\Models\Admin\AdminRoleWidget;
use App\Models\Admin\RolePermissions;
use Illuminate\Http\Request;
use DB;


class RoleController extends Controller
{

    /**
     * showdoc
     * @catalog 后台/角色管理
     * @title 获取所有角色接口_v1.0
     * @description 获取所有角色接口_v1.0
     * @method POST
     * @url http://xx.com/admin/role/getRole
     * @header token 必选 string 设备token
     * @return {"error_code": 2001,"data": [{"id": 1,"name": "超级管理员","slug": "admin","created_at": "2020-09-24T03:27:49.000000Z","updated_at": "2020-09-24T03:27:49.000000Z"},{"id": 3,"name": "超级管理员1","slug": "admin1","created_at": "2020-09-24T03:43:19.000000Z","updated_at": "2020-09-24T03:43:19.000000Z"}],"message": "success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @return_param name string 角色名称
     * @return_param slug string 角色标识
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/24
     * @TIME: 2:26 下午
     */
    public function getRole()
    {
        if (self::GDT('role')) {
            return response('', 403);
        }
        $temp = AdminRole::get();
        return apiResponse(2001, $temp);
    }

    /**
     * showdoc
     * @catalog 后台/角色管理
     * @title 添加角色接口_v1.0
     * @description 添加角色接口_v1.0
     * @method POST
     * @url http://xx.com/admin/role/addRole
     * @header token 必选 string 设备token
     * @param name 必选 string 角色名称
     * @param slug 必选 string 角色标识
     * @return {"error_code": 2001,"data": [],"message": "success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/24
     * @TIME: 11:22 上午
     * @throws
     */
    public function addRole(Request $request)
    {
        if (self::GDT('role')) {
            return response('', 403);
        }
        $this->validate($request, [
            "name" => "required|unique:admin_roles",
            "slug" => "required|unique:admin_roles",
        ], [
            "name.required" => "角色不能为空",
            "name.unique" => "角色名已经存在",
            "slug.required" => "角色标识不能为空",
            "slug.unique" => "角色标识已经存在",
        ]);
        $data = ['name' => $request->name, 'slug' => $request->slug];
        try {
            $res = AdminRole::create($data);
            return apiResponse(2001, $res);
        } catch (\Exception $exception) {
            return apiResponse(2005, [], $exception->getMessage());
        }
    }

    /**
     * showdoc
     * @catalog 后台/角色管理
     * @title 更新角色接口_v1.0
     * @description 更新角色接口_v1.0
     * @method POST
     * @url http://xx.com/admin/role/updateRole
     * @header token 必选 string 设备token
     * @param id 必选 int id
     * @param name 必选 string 角色名
     * @param slug 必选 string 角色标识
     * @return {"error_code": 2001,"data": [],"message": "success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @remark 这里是备注信息
     * @throws
     * @number 99
     * @DATE: 2020/9/24
     * @TIME: 1:13 下午
     */
    public function updateRole(Request $request)
    {
        if (self::GDT('role')) {
            return response('', 403);
        }
        $this->validate($request, [
            "id" => "required",
            "name" => "required",
            "slug" => "required",
        ]);
        $data = ['name' => $request->name, 'slug' => $request->slug];
        $temp = AdminRole::find($request->id);
        try {
            $temp->update($data);
            return apiResponse(2001);
        } catch (\Exception $exception) {
            return apiResponse(2005, [], $exception->getMessage());
        }
    }

    /**
     * showdoc
     * @catalog 后台/角色管理
     * @title 删除角色接口_v1.0
     * @description 删除角色接口_v1.0
     * @method POST
     * @url http://xx.com/admin/role/delRole
     * @header token 必选 string 设备token
     * @param id 必选 int 角色ID
     * @return {"error_code": 2001,"data": [],"message": "success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/24
     * @TIME: 1:17 下午
     * @throws
     */
    public function delRole(Request $request)
    {
        $this->validate($request, [
            "id" => "required",
        ]);
        try {
            return DB::transaction(function () use ($request) {
                try {
                    AdminRole::where('id', $request->id)->delete();                   #角色表
                    AdminRoleMenu::where('role_id', $request->id)->delete();          #角色菜单关联表
                    AdminRoleUser::where('role_id', $request->id)->delete();          #角色用户关联表
                    AdminRoleWidget::where('role_id', $request->id)->delete();        #角色组件关联表
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
     * @catalog 后台/角色管理
     * @title 角色详情接口
     * @description 角色详情接口
     * @method POST
     * @url http://xx.com/admin/role/getRoleDetail
     * @header token 必选 string 设备token
     * @param id 必选 int 角色ID
     * @return {"error_code": 2001,"data": {"role": {"id": 1,"name": "超级管理员","slug": "admin","created_at": "2020-09-24 11:27:49","updated_at": "2020-09-24 11:27:49"},"rp": [{"role_id": 1,"permission_id": 1,"name": "超级权限","slug": "suppppper"},{"role_id": 1,"permission_id": 2,"name": "权限管理","slug": "permission"},{"role_id": 1,"permission_id": 3,"name": "菜单管理","slug": "menu"},{"role_id": 1,"permission_id": 4,"name": "系统配置","slug": "config"},{"role_id": 1,"permission_id": 5,"name": "角色管理","slug": "role"}]},"message": "success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @return_param role array 角色信息
     * @return_param rp array 角色所拥有的权限
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/27
     * @TIME: 4:57 下午
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getRoleDetail(Request $request)
    {
        $this->validate($request, ['id' => 'required']);
        $role = AdminRole::find($request->id);
        $res = RolePermissions::LeftJoin('admin_permissions', 'admin_role_permissions.permission_id', '=', 'admin_permissions.id')
            ->where('admin_role_permissions.role_id', $request->id)
            ->select(
                'admin_role_permissions.role_id',
                'admin_role_permissions.permission_id',
                'admin_permissions.name',
                'admin_permissions.slug'
            )->get();
        return apiResponse(2001, ['role' => $role, 'rp' => $res]);
    }

}
