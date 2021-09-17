<?php
namespace  App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Permissions;
use App\Models\Admin\RolePermissions;
use Illuminate\Http\Request;
use Exception;
use DB;


class PermissionsController extends Controller
{

    /**
     * showdoc
     * @catalog 后台/权限管理
     * @title 获取所有权限接口
     * @description 获取所有权限接口
     * @method POST
     * @url http://xx.com/admin/permissions/getPermission
     * @header token 必选 string 设备token
     * @return {"error_code": 2001,"data": [{"id": 1,"name": "超级权限","slug": "suppppper","created_at": null,"updated_at": null}],"message": "success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @return_param name string 权限名称
     * @return_param slug string 权限标识
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/25
     * @TIME: 9:20 上午
     */
    public function getPermission(){
        if (self::GDT('permission')){
            return response('',403);
        }
        $temp = Permissions::get();
        return apiResponse(2001,$temp);
    }

    /**
     * showdoc
     * @catalog 后台/权限管理
     * @title 添加权限接口
     * @description 添加权限接口
     * @method POST
     * @url http://xx.com/admin/permissions/addPermission
     * @header token 必选 string 设备token
     * @param name 必选 string 权限名
     * @param slug 必选 string 权限标识
     * @return {"error_code": 2001,"data": [],"message": "success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/25
     * @TIME: 9:35 上午
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addPermission(Request $request){
        if (self::GDT('permission')){
            return response('',403);
        }
        $this->validate($request,[
            "name" => "required",
            "slug" => "required|unique:admin_permissions"
        ]);
        $data = ['name' => $request->name,'slug' => $request->slug];
        try{
            $res = Permissions::create($data);
            return apiResponse(2001,$res);
        }catch (\Exception $exception){
            return apiResponse(2005,[],$exception->getMessage());
        }
    }

    /**
     * showdoc
     * @catalog 后台/权限管理
     * @title 删除权限接口
     * @description 删除权限接口
     * @method POST
     * @url http://xx.com/admin/permissions/delPermission
     * @header token 必选 string 设备token
     * @param id 必选 id 权限ID
     * @return {"error_code": 2001,"data": [],"message": "success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/25
     * @TIME: 9:46 上午
     * @throws \Illuminate\Validation\ValidationException
     */
    public function delPermission(Request $request){
        if (self::GDT('permission')){
            return response('',403);
        }
        $this->validate($request,[
            "id" => "required"
        ]);
        if ($request->id == 1){
            return apiResponse(2002,[],'此权限无法删除');
        }
        DB::beginTransaction();
        try{
            Permissions::where('id',$request->id)->delete();                #删除权限
            RolePermissions::where('permission_id',$request->id)->delete(); #删除与角色的关联
            DB::commit();;
            return apiResponse(2001);
        }catch (Exception $exception){
            DB::rollBack();
            return apiResponse(2005,[],$exception->getMessage());
        }
    }

    /**
     * showdoc
     * @catalog 后台/权限管理
     * @title 修改权限接口
     * @description 修改权限接口
     * @method POST
     * @url http://xx.com/admin/permissions/updatePermission
     * @header token 必选 string 设备token
     * @param id 必选 string 权限ID
     * @param name 必选 string 权限名称
     * @param slug 必选 string 权限标识
     * @return {"error_code": 2001,"data": [],"message": "success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/25
     * @TIME: 9:52 上午
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updatePermission(Request $request){
        if (self::GDT('permission')){
            return response('',403);
        }
        $this->validate($request,[
            "id" => "required",
            "name" => "required",
            "slug" => "required"
        ]);
        $data = ['name' => $request->name,'slug' => $request->slug];
        try{
            Permissions::where('id',$request->id)->update($data);
            $data['id'] = $request->id;
            return apiResponse(2001,$data);
        }catch (Exception $exception){
            return apiResponse(2005,[],$exception->getMessage());
        }
    }

}
