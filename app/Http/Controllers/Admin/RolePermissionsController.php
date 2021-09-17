<?php
namespace  App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\RolePermissions;
use Illuminate\Http\Request;
use Exception;
use DB;
class RolePermissionsController extends Controller
{

    /**
     * showdoc
     * @catalog 后台/权限管理
     * @title 获取角色关联的权限接口
     * @description 获取角色关联的权限接口
     * @method POST
     * @url http://xx.com/admin/rolePermissions/getRP
     * @header token 必选 string 设备token
     * @param id 必选 int 角色ID
     * @return [{"role_id": 1,"permission_id": 1,"name": "超级权限","slug": "suppppper"},{"role_id": 1,"permission_id": 2,"name": "权限管理","slug": "permission"},{"role_id": 1,"permission_id": 3,"name": "菜单管理","slug": "menu"},{"role_id": 1,"permission_id": 4,"name": "系统配置","slug": "config"},{"role_id": 1,"permission_id": 5,"name": "角色管理","slug": "role"}]
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @return_param role_id int 角色ID
     * @return_param permission_id int 权限ID
     * @return_param name string 权限名称
     * @return_param slug string 权限标识
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/25
     * @TIME: 10:47 上午
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getRP(Request $request) {
        if (self::GDT('role')){
            return response('',403);
        }
        $this->validate($request,[
            "id" => "required"
        ]);
        $rp = RolePermissions::LeftJoin('admin_permissions','admin_role_permissions.permission_id','=','admin_permissions.id')
            ->where('admin_role_permissions.role_id',$request->id)
            ->select(
                'admin_role_permissions.role_id',
                'admin_role_permissions.permission_id',
                'admin_permissions.name',
                'admin_permissions.slug'
            )->get();
        return apiResponse(2001,$rp);
    }


    /**
     * showdoc
     * @catalog 后台/权限管理
     * @title 修改角色权限接口
     * @description 修改角色权限接口
     * @method POST
     * @url http://xx.com/admin/rolePermissions/updateRP
     * @header token 必选 string 设备token
     * @param role_id 必选 int 角色ID
     * @param permission_ids 必选 array 权限ID组
     * @return {"error_code": 2001,"data": [],"message": "success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/25
     * @TIME: 11:02 上午
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateRP(Request $request){
        if (self::GDT('role')){
            return response('',403);
        }
        $this->validate($request,[
            'role_id' => "required",
            'permission_ids' => "required",
        ]);
        $data = [];
        foreach ($request->permission_ids as $permission_id) {
            array_push($data,['role_id' => $request->role_id,'permission_id' => $permission_id]);
        }
        DB::beginTransaction();
        try{
            RolePermissions::where('role_id',$request->role_id)->delete();
            if (count($data) > 0){
                DB::table('admin_role_permissions')->insert($data);
            }
            DB::commit();
            return apiResponse(2001);
        }catch (Exception $exception){
            DB::rollBack();
            return apiResponse(2005,[],$exception->getMessage());
        }
    }
}
