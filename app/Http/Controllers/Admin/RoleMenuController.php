<?php
namespace  App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\RoleMenu;
use App\Models\Admin\RolePermissions;
use Illuminate\Http\Request;
use DB;
use Exception;
class RoleMenuController extends Controller
{
    /**
     * showdoc
     * @catalog 后台/菜单管理
     * @title 获取角色关联的菜单接口
     * @description 获取角色关联的菜单接口
     * @method POST
     * @url http://xx.com/admin/roleMenu/getRM
     * @header token 必选 string 设备token
     * @param id 必选 int 角色ID
     * @return {"error_code": 2001,"data": [{"role_id": 1,"menu_id": 1,"parent_id": 0,"title": "设置","slug": "setting"},{"role_id": 1,"menu_id": 2,"parent_id": 1,"title": "用户设置","slug": "setting.user"},{"role_id": 1,"menu_id": 3,"parent_id": 1,"title": "角色管理","slug": "setting.role"},{"role_id": 1,"menu_id": 4,"parent_id": 0,"title": "主页","slug": "manager"},{"role_id": 1,"menu_id": 5,"parent_id": 1,"title": "环境设置","slug": "setting.option"}],"message": "success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @return_param role_id int 角色ID
     * @return_param menu_id int 权限ID
     * @return_param parent_id string 上级菜单ID
     * @return_param slug string 菜单标识
     * @return_param title string 菜单名称
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/25
     * @TIME: 10:47 上午
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getRM(Request $request) {
        if (self::GDT('role')){
            return response('',403);
        }
        $this->validate($request,[
            "id" => "required"
        ]);
        $rp = RoleMenu::LeftJoin('admin_menu','admin_role_menu.menu_id','=','admin_menu.id')
            ->where('admin_role_menu.role_id',$request->id)
            ->select(
                'admin_role_menu.role_id',
                'admin_role_menu.menu_id',
                'admin_menu.parent_id',
                'admin_menu.title',
                'admin_menu.slug'
            )->get();
        return apiResponse(2001,$rp);
    }

    /**
     * showdoc
     * @catalog 后台/菜单管理
     * @title 修改角色菜单接口
     * @description 修改角色菜单接口
     * @method POST
     * @url http://xx.com/admin/rolemenu/updateRM
     * @header token 必选 string 设备token
     * @param role_id 必选 int 角色ID
     * @param menu_ids 必选 array 菜单ID组
     * @return {"error_code": 2001,"data": [],"message": "success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/25
     * @TIME: 11:02 上午
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Throwable
     */
    public function updateRM(Request $request){
        if (self::GDT('menu')){
            return response('',403);
        }
        $this->validate($request,[
            'role_id' => "required",
            'menu_ids' => "required"
        ]);
        $data = [];
        foreach ($request->menu_ids as $menu_id) {
            array_push($data,['role_id' => $request->role_id,'menu_id' => $menu_id]);
        }
        DB::beginTransaction();
        try{
            RoleMenu::where('role_id',$request->role_id)->delete();
            if (count($data) > 0){
                DB::table('admin_role_menu')->insert($data);
            }
            DB::commit();
            return apiResponse(2001);
        }catch (Exception $exception){
            DB::rollBack();
            return apiResponse(2005,[],$exception->getMessage());
        }
    }
}
