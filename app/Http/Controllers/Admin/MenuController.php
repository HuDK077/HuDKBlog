<?php
namespace  App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Menu;
use App\Models\Admin\RoleMenu;
use App\Models\Admin\RoleUser;
use Illuminate\Http\Request;
use DB;
use Exception;

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
     * @return {"error_code": 2001,"data": [],"message": "success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/24
     * @TIME: 5:40 下午
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateMenu(Request $request){
        if (self::GDT('menu')){
            return response('',403);
        }
        $this->validate($request,[
            'id' => "required",
//            'role_id' => "required",
            'parent_id' => "required",
            'slug' => "required",
            'title' => "required",
        ]);
        $data = ['parent_id' => $request->parent_id,'slug' => $request->slug,'title' => $request->title];
        DB::beginTransaction();
        try{
            Menu::where('id',$request->id)->update($data);
//            RoleMenu::where('menu_id',$request->id)->update(['role_id' => $request->role_id]);
            $data['id'] = $request->id;
            DB::commit();
            return apiResponse(2001,$data);
        }catch (Exception $exception){
            DB::rollBack();
            return apiResponse(2005,[],$exception->getMessage());
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
    public function delMenu(Request $request){
        if (self::GDT('menu')){
            return response('',403);
        }
        $this->validate($request,[
            'id' => "required"
        ]);
        DB::beginTransaction();
        try{
            Menu::where('id',$request->id)->delete();           #删除菜单
            Menu::where('parent_id',$request->id)->delete();    #删除菜单下面的子菜单
            RoleMenu::where('menu_id',$request->id)->delete();  #删除权限所关联的菜单
            DB::commit();
            return apiResponse(2001);
        }catch (Exception $exception){
            DB::rollBack();
            return apiResponse(2005,[],$exception->getMessage());
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
     * @return array {"error_code": 2001,"data": [],"message": "success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/24
     * @TIME: 5:01 下午
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addMenu(Request $request){
        if (self::GDT('menu')){
            return response('',403);
        }
        $this->validate($request,[
//            'sort' => "required",
            'parent_id' => "required",
            'slug' => "required|unique:admin_menu",
            'title' => "required",
        ]);
        $data = ['parent_id' => $request->parent_id,'slug' => $request->slug,'title' => $request->title];
        DB::beginTransaction();
        try{
            $res = Menu::create($data);
//            $menu_id = $res->id;
//            $role_menu_data = ['role_id' => $request->role_id,'menu_id' => $menu_id];
//            RoleMenu::create($role_menu_data);
            DB::commit();
            return apiResponse(2001,$res);
        }catch (Exception $exception){
            DB::rollBack();
            return apiResponse(2005,[],$exception->getMessage());
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
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/24
     * @TIME: 4:41 下午
     */
    public function getAllMenu() {
        if (self::GDT('menu')){
            return response('',403);
        }
        $menus = Menu::orderBy('sort','desc')->get();
        return apiResponse(2001,$menus);
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
    public function getUserMenu(){
        $user = auth('admin')->user();
        $role = RoleUser::where('user_id',$user->id)->first();
        $r_m = RoleMenu::where('role_id',$role->role_id)->pluck('menu_id'); #菜单ID组
        $menus = Menu::whereIn('id',$r_m)->orderBy('sort','desc')->pluck('slug');
        return apiResponse(2001,$menus);
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
     * @param sort 必选 array ID组
     * @return {"error_code": 2001,"data": [],"message": "success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/30
     * @TIME: 5:33 下午
     * @throws \Illuminate\Validation\ValidationException
     */
    public function menuSort(Request $request){
        if (self::GDT('menu')){
            return response('',403);
        }
        $this->validate($request,[
            'sort' => 'required'
        ]);
        try{
            $data = $request->sort;
            foreach ($data as $key => $item) {
                Menu::where('id',$item)->update(['sort' => $key]);
            }
            return apiResponse(2001);
        }catch (Exception $exception){
            return apiResponse(2005,$exception->getMessage());
        }
    }
}
