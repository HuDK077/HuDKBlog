<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AdminRoleMenu;
use App\Models\Admin\AdminRoleWidget;
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
     * @return {"error_code": 2001, "data": { "menuIds": [ 2, 1 ], "widgetIds": [ 2, 4, 3 ] }, "message": "success" }
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @return_param menuIds array 菜单数组,包含以下两个字段
     * @return_param menu_id int 菜单ID
     * @return_param parent_id int 菜单上级ID
     * @return_param widgetIds array 组件ID数组
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/25
     * @TIME: 10:47 上午
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getRM(Request $request)
    {
        $this->validate($request, [
            "id" => "required"
        ]);
//        $menus = AdminRoleMenu::LeftJoin('admin_menus', 'admin_role_menus.menu_id', '=', 'admin_menus.id')
//            ->where('admin_role_menus.role_id', $request->id)
//            ->select(
//                'admin_role_menus.menu_id',
//                'admin_menus.parent_id'
//            )->get();
        $menuIds = AdminRoleMenu::where('role_id', $request->id)->pluck('menu_id');
        $widgetIds = AdminRoleWidget::where('role_id', $request->id)->pluck('widget_id');
        return apiResponse(2001, ['menuIds' => $menuIds, 'widgetIds' => $widgetIds]);
    }

    /**
     * showdoc
     * @catalog 后台/菜单管理
     * @title 修改角色菜单接口
     * @description 修改角色菜单接口
     * @method POST
     * @url http://xx.com/admin/roleMenu/updateRM
     * @header token 必选 string 设备token
     * @param role_id 必选 int 角色ID
     * @param menu_ids 必选 array 菜单ID组
     * @param widget_ids 必选 array 组件ID组
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
    public function updateRM(Request $request)
    {
        $this->validate($request, [
            'role_id' => "required",
            'menu_ids' => "required",
            'widget_ids' => "required"
        ]);
        $menuArray = [];
        foreach ($request->menu_ids as $menu_id) {
            array_push($menuArray, ['role_id' => $request->role_id, 'menu_id' => $menu_id]);
        }
        $widgetArray = [];
        foreach ($request->widget_ids as $widget_id) {
            array_push($widgetArray, ['role_id' => $request->role_id, 'widget_id' => $widget_id]);
        }
        try {
            return DB::transaction(function () use ($request, $menuArray, $widgetArray) {
                try {
                    AdminRoleMenu::where('role_id', $request->role_id)->delete();
                    if (count($menuArray) > 0) {
                        DB::table('admin_role_menus')->insert($menuArray);
                    }
                    AdminRoleWidget::where('role_id', $request->role_id)->delete();
                    if (count($widgetArray) > 0) {
                        DB::table('admin_role_widgets')->insert($widgetArray);
                    }
                    return apiResponse(2001);
                } catch (\Exception $exception) {
                    throw new \Exception($exception->getMessage());
                }
            });
        } catch (\Exception $exception) {
            return apiResponse(2005, [], $exception->getMessage());
        }

    }
}
