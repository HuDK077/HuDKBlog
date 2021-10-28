<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AdminMenu;
use App\Models\Admin\AdminRoleMenu;
use App\Models\Admin\AdminRoleUser;
use App\Models\Admin\AdminUser;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{

    /**
     * showdoc
     * @catalog 后台/菜单管理
     * @title 更新菜单接口
     * @description 更新菜单接口
     * @method POST
     * @url http://xx.com/admin/menu/updateMenu
     * @header token 必选 string 设备token
     * @param id 必选 int 菜单ID
     * @param parent_id 必选 int 上级菜单ID
     * @param slug 必选 string 菜单标识
     * @param title 必选 string 菜单标题
     * @param path 必选 string 路径
     * @param meta 必选 string 配置
     * @param redirect 不必选 string 重定向
     * @param icon 不必选 string 图标
     * @param file 不必选 string 文件
     * @return {"error_code": 2001, "data": { "parent_id": "0", "slug": "slug1", "title": "title", "path": "title", "redirect": "title", "meta": "title", "icon": "title", "id": "3" }, "message": "success" }
     * @return_param error_code int 返回码
     * @return_param message string 返回说明,2001成功
     * @return_param parent_id int 上级菜单ID
     * @return_param slug string 菜单标识
     * @return_param title string 菜单标题
     * @return_param path string 路径
     * @return_param meta json 配置
     * @return_param redirect string 重定向
     * @return_param icon string 图标
     * @return_param file string 文件
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/24
     * @TIME: 5:40 下午
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateMenu(Request $request)
    {
        $this->validate($request, [
            'id' => "required",
            'parent_id' => "required",
            'slug' => "required",
            'title' => "required",
            'path' => "required",
//            'redirect' => "required",
            'meta' => "required",
//            'icon' => "required",
        ]);
        $data = [
            'parent_id' => $request->parent_id,
            'slug' => $request->slug,
            'title' => $request->title,
            'path' => $request->path,
            'redirect' => $request->redirect,
            'meta' => $request->meta,
            'icon' => $request->icon,
            'file' => $request->file,
        ];
//        DB::beginTransaction();
//        try{
//            AdminMenu::where('id',$request->id)->update($data);
////            RoleMenu::where('menu_id',$request->id)->update(['role_id' => $request->role_id]);
//            $data['id'] = $request->id;
//            DB::commit();
//            return apiResponse(2001,$data);
//        }catch (Exception $exception){
//            DB::rollBack();
//            return apiResponse(2005,[],$exception->getMessage());
//        }
        try {
            return DB::transaction(function () use ($request, $data) {
                try {
                    AdminMenu::where('id', $request->id)->update($data);
                    $data['id'] = $request->id;
                    return apiResponse(2001, $data);
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
     * @catalog 后台/菜单管理
     * @title 删除菜单接口
     * @description 删除菜单接口
     * @method POST
     * @url http://xx.com/admin/menu/delMenu
     * @header token 必选 string 设备token
     * @param id 必选 int 菜单ID
     * @return {"error_code": 2001,"data": [],"message": "success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/24
     * @TIME: 5:13 下午
     * @throws \Illuminate\Validation\ValidationException
     */
    public function delMenu(Request $request)
    {
        $this->validate($request, [
            'id' => "required"
        ]);
//        DB::beginTransaction();
//        try {
//            AdminMenu::where('id', $request->id)->delete();           #删除菜单
//            AdminMenu::where('parent_id', $request->id)->delete();    #删除菜单下面的子菜单
//            AdminRoleMenu::where('menu_id', $request->id)->delete();  #删除权限所关联的菜单
//            DB::commit();
//            return apiResponse(2001);
//        } catch (Exception $exception) {
//            DB::rollBack();
//            return apiResponse(2005, [], $exception->getMessage());
//        }

        try {
            return DB::transaction(function () use ($request) {
                try {
                    AdminMenu::where('id', $request->id)->delete();           #删除菜单
                    AdminMenu::where('parent_id', $request->id)->delete();    #删除菜单下面的子菜单
                    AdminRoleMenu::where('menu_id', $request->id)->delete();  #删除权限所关联的菜单
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
     * @catalog 后台/菜单管理
     * @title 添加菜单接口
     * @description 添加菜单接口
     * @method POST
     * @url http://xx.com/admin/menu/addMenu
     * @header token 必选 string 设备token
     * @param parent_id 必选 int 上级菜单ID
     * @param slug 必选 string 菜单标识
     * @param title 必选 string 菜单标题
     * @param path 必选 string 路径
     * @param meta 必选 string 配置
     * @param redirect 不必选 string 重定向
     * @param icon 不必选 string 图标
     * @param file 不必选 string file
     * @return {"error_code": 2001, "data": { "parent_id": "0", "slug": "slug1", "title": "title", "path": "title", "redirect": "title", "meta": "title", "icon": "title", "updated_at": "2021-10-22 10:21:39", "created_at": "2021-10-22 10:21:39", "id": 3 }, "message": "success" }
     * @return_param error_code int 返回码
     * @return_param message string 返回说明,2001成功
     * @return_param parent_id int 上级菜单ID
     * @return_param slug string 菜单标识
     * @return_param title string 菜单标题
     * @return_param path string 路径
     * @return_param meta json 配置
     * @return_param redirect string 重定向
     * @return_param icon string 图标
     * @return_param file string file
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/24
     * @TIME: 5:01 下午
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addMenu(Request $request)
    {
        $this->validate($request, [
//            'sort' => "required",
            'parent_id' => "required",
            'slug' => "required|unique:admin_menus",
            'title' => "required",
            'path' => "required",
//            'redirect' => "required",
            'meta' => "required",
//            'icon' => "required",
        ]);
        $data = [
            'parent_id' => $request->parent_id,
            'slug' => $request->slug,
            'title' => $request->title,
            'path' => $request->path,
            'redirect' => $request->redirect,
            'meta' => $request->meta,
            'icon' => $request->icon,
            'file' => $request->file,
        ];
//        DB::beginTransaction();
//        try{
//            $res = AdminMenu::create($data);
////            $menu_id = $res->id;
////            $role_menu_data = ['role_id' => $request->role_id,'menu_id' => $menu_id];
////            RoleMenu::create($role_menu_data);
//            DB::commit();
//            return apiResponse(2001,$res);
//        }catch (Exception $exception){
//            DB::rollBack();
//            return apiResponse(2005,[],$exception->getMessage());
//        }

        try {
            return DB::transaction(function () use ($request, $data) {
                try {
                    $res = AdminMenu::create($data);
                    return apiResponse(2001, $res);
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
     * @catalog 后台/菜单管理
     * @title 获取所有菜单接口
     * @description 获取所有菜单接口
     * @method POST
     * @url http://xx.com/admin/menu/getAllMenu
     * @header token 必选 string 设备token
     * @return array {"error_code": 2001,"data": [{"id": 1,"parent_id": 0,"title": "文章管理","slug": "index","created_at": null,"updated_at": null},{"id": 2,"parent_id": 1,"title": "添加管理","slug": "user.edit","created_at": null,"updated_at": null}],"message": "success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @return_param parent_id int 上级菜单ID
     * @return_param title string 菜单名称
     * @return_param slug string 菜单标识
     * @return_param path string 路径
     * @return_param meta json 配置
     * @return_param redirect string 重定向
     * @return_param icon string 图标
     * @return_param file string 文件
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/24
     * @TIME: 4:41 下午
     */
    public function getAllMenu()
    {
        $menus = AdminMenu::orderBy('sort', 'desc')->get();
        return apiResponse(2001, $menus);
    }

    /**
     * showdoc
     * @catalog 后台/菜单管理
     * @title 获取个人权限菜单接口_v1.0
     * @description 获取个人权限菜单接口_v1.0
     * @method POST
     * @url http://xx.com/admin/menu/getUserMenu
     * @header token 必选 string 设备token
     * @return {"error_code": 2001,"data": ["index","user.edit"],"message": "success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @return_param data array 菜单标识组
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/24
     * @TIME: 4:13 下午
     */
    public function getUserMenu()
    {
        $user = auth('admin')->user();
//        $role = AdminRoleUser::where('user_id', $user->id)->first();
//        $r_m = AdminRoleMenu::where('role_id', $role->role_id)->pluck('menu_id'); #菜单ID组
//        $menus = AdminMenu::whereIn('id', $r_m)->orderBy('sort', 'desc')->pluck('slug');

//        $menus = AdminMenu::leftJoin('admin_role_menus','admin_role_menus.menu_id','=','admin_menus.id')
//            ->leftJoin('admin_role_users','admin_role_users.role_id','=','admin_role_menus.role_id')
//            ->where('admin_role_users.id',$user->id)
//            ->pluck('admin_menus.slug');

        $menus = AdminUser::leftJoin('admin_role_users', 'admin_role_users.user_id', '=', 'admin_users.id')
            ->leftJoin('admin_role_menus', 'admin_role_menus.role_id', '=', 'admin_role_users.role_id')
            ->leftJoin('admin_menus', 'admin_menus.id', '=', 'admin_role_menus.menu_id')
            ->where('admin_users.id', $user->id)
            ->orderBy('admin_menus.sort', 'desc')
            ->pluck('admin_menus.slug')
            ->toArray();
        $res = array_values(array_unique(array_filter($menus)));
        return apiResponse(2001, $res);
    }


    /**
     * 菜单排序
     * showdoc
     * @catalog 后台/菜单管理
     * @title 菜单排序
     * @description 菜单排序
     * @method POST
     * @url http://xx.com/admin/menu/menuSort
     * @header token 必选 string 设备token
     * @param sort 必选 array ID组[$id]
     * @return {"error_code": 2001,"data": [],"message": "success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/30
     * @TIME: 5:33 下午
     * @throws \Illuminate\Validation\ValidationException
     */
    public function menuSort(Request $request)
    {
        $this->validate($request, [
            'sort' => 'required'
        ]);
        try {
            $data = $request->sort;
            $in = implode(',', array_values($data));
            $where = '';
            foreach ($data as $key => $value) {
                $where .= " when {$value} then {$key} ";
            }
            DB::update("update admin_menus set sort= case id {$where} END where id in ({$in})");
            return apiResponse(2001, [], '成功');
        } catch (\Exception $exception) {
            return apiResponse(2005, [], $exception->getMessage());
        }
    }
}
