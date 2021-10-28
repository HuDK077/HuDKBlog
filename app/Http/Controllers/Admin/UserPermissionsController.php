<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AdminUser;
use App\Models\Admin\AdminUserMenu;
use App\Models\Admin\AdminUserWidget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserPermissionsController extends Controller
{
    /**
     * showdoc
     * @catalog 后台/用户权限
     * @title 获取用户权限
     * @description  方官喜
     * @method POST
     * @url http://xx.com/admin/userPermissions/getPermission
     * @header token 必选 string 设备token
     * @param id 必选 int 用户id
     * @return {"error_code": 2001, "data": { "id": 1, "username": "admin", "password": "$2y$10$prNSSNdsyv.BnOffiSdd1efNjHFoWPw3fBIQwdCM5/d0xj6KDOHQO", "name": "超级管理员", "avatar": "74b9698dcb0ea6c97837403c45690ad4", "remember_token": null, "created_at": "2020-12-20 20:20:20", "updated_at": "2020-10-15 12:51:21", "role_menus": [ 1, 2, 3, 4, 5, 6, 7 ], "menus": [ 1, 2, 3, 4, 5, 6, 7 ], "role_widgets": [ 2, 3, 4, 5, 6, 8, 9, 10, 11, 12 ], "widgets": [ 2, 4 ] }, "message": "success" }
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @return_param id int 用户id
     * @return_param username string 用户登陆名称
     * @return_param name string 用户名称
     * @return_param avatar string 用户头像
     * @return_param role_menus array 角色下的菜单id数组
     * @return_param menus array 私有菜单id数组
     * @return_param role_widgets array 角色下的组件id数组
     * @return_param widgets array 私有组件id数组
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2021/10/26
     * @TIME: 上午11:07
     */
    public function getPermission(Request $request)
    {
        $this->validate($request, [
            'id' => 'required'
        ]);
        $user = AdminUser::find($request->id)->with([
            'role_menus' => function ($query) {
                $query->select('admin_role_menus.menu_id');
            },
            'menus',
            'role_widgets' => function ($query) {
                $query->select('admin_role_widgets.widget_id');
            },
            'widgets'
        ])
            ->first()
            ->toArray();
        $r_m = [];
        foreach ($user['role_menus'] as $role_menu) {
            array_push($r_m, $role_menu['menu_id']);
        }
        $user['role_menus'] = array_values(array_unique(array_filter($r_m)));
        $m = [];
        foreach ($user['menus'] as $menu) {
            array_push($m, $menu['id']);
        }
        $user['menus'] = array_values(array_unique(array_filter($m)));
        $r_w = [];
        foreach ($user['role_widgets'] as $role_widget) {
            array_push($r_w, $role_widget['widget_id']);
        }
        $user['role_widgets'] = array_values(array_unique(array_filter($r_w)));
        $w = [];
        foreach ($user['widgets'] as $widget) {
            array_push($w, $widget['id']);
        }
        $user['widgets'] = array_values(array_unique(array_filter($w)));
        return apiResponse(2001, $user);
    }

    /**
     * showdoc
     * @catalog 后台/用户权限
     * @title 更新用户权限(私有部分)
     * @description  方官喜
     * @method POST
     * @url http://xx.com/admin/userPermissions/updatePermission
     * @header token 必选 string 设备token
     * @param id 必选 int 用户id
     * @param menu_ids 必选 array 私有菜单id数组
     * @param widget_ids 必选 array 私有组件id数组
     * @return {"error_code": 2001, "data": [], "message": "success" }
     * @return_param error_code int 返回码
     * @return_param message string 返回说明,2001成功
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2021/10/26
     * @TIME: 下午5:05
     */
    public function updatePermission(Request $request)
    {
        $this->validate($request, [
            'id' => "required",
            'menu_ids' => "required",
            'widget_ids' => "required"
        ]);
        $menuArray = [];
        foreach ($request->menu_ids as $menu_id) {
            array_push($menuArray, ['user_id' => $request->id, 'menu_id' => $menu_id]);
        }
        $widgetArray = [];
        foreach ($request->widget_ids as $widget_id) {
            array_push($widgetArray, ['user_id' => $request->id, 'widget_id' => $widget_id]);
        }
        try {
            return DB::transaction(function () use ($request, $menuArray, $widgetArray) {
                try {
                    AdminUserMenu::where('user_id', $request->id)->delete();
                    if (count($menuArray) > 0) {
                        DB::table('admin_user_menus')->insert($menuArray);
                    }
                    AdminUserWidget::where('user_id', $request->id)->delete();
                    if (count($widgetArray) > 0) {
                        DB::table('admin_user_widgets')->insert($widgetArray);
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
